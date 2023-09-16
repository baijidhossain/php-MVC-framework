<?php

class FileManager {

	public $path = NULL; // path to scan
	public $mode = 'Files'; // Files or Images
	public $allowed_ext = NULL; // jpe?g|pdf|docx?
	public $max_file_size = 0; // in megabytes (0 = unlimited)

	public function run() {

		if ( ! is_dir( $this->path ) || ! is_readable( $this->path ) ) {
			throw new Exception( 'Path is not set' );
		}

		// check if request parameters contains any ../ characters
		$this->recursiveDotCheck( $_REQUEST );

		if ( $this->mode === 'Images' && empty( $this->allowed_ext ) ) {
			$this->allowed_ext = 'jpe?g|png|webp|svg';
		}

		// create directory if task is to create a new folder
		if ( isset( $_GET['task'] ) ) {
			switch ( $_GET['task'] ) {
				case 'createFolder':
					$this->createFolder( $_POST["path"], $_POST["folderName"] );
					break;
				case 'rename':
					$this->renameFolder( $_POST["currentPath"], $_POST["oldName"], $_POST["newName"] );
					break;
				case 'changeDir':
					$this->changeFolder( $_POST["chdir"] );
					break;
				case 'copy':
					$this->copyItem( $_POST["currentPath"], $_POST["copyPath"], $_POST["files"] );
					break;
				case 'move':
					$this->moveItem( $_POST["currentPath"], $_POST["movePath"], $_POST["files"] );
					break;
				case 'delete':
					$this->deleteItem( $_POST["currentPath"], $_POST["files"] );
					break;
				case 'upload':
					$this->uploadFiles( $_POST["uploadPath"], $_FILES['fileItem'] );
					break;
				default:
					# code...
					break;
			}
		}

		// if download is set, download the file
		if ( isset( $_GET['download'] ) ) {
			$this->downloadFile( $_GET['download'] );
		}

		// change directory if set
		if ( isset( $_GET['chdir'] ) ) {
			$this->changeFolder( $_GET['chdir'] );
		}

		$fileList = $this->getFileList( $this->path, $this->allowed_ext );

		return $this->renderHtml( $fileList );
	}

	public function formatBytes( $bytes ) {
		$base   = log( $bytes ) / log( 1024 );
		$suffix = [ "byte", "KB", "MB", "GB" ][ floor( $base ) ];

		return round( 1024 ** ( $base - floor( $base ) ), 2 ) . ' ' . $suffix;
	}

	private function getFileList( $dir, $ext = NULL ) {
		// array to hold return value
		$retval = [
			'folders' => [],
			'files'   => [],
		];

		// add trailing slash if missing
		if ( substr( $dir, - 1 ) !== "/" ) {
			$dir .= "/";
		}

		// open directory for reading
		$d = new DirectoryIterator( $dir ) or die( "getFileList: Failed opening directory $dir for reading" );

		if ( $ext ) {
			// filter files
			$iterator = new FileExtFilter( $d, $ext );
		} else {
			$iterator = $d;
		}

		foreach ( $iterator as $fileinfo ) {
			// skip hidden files
			if ( $fileinfo->isDot() ) {
				continue;
			}

			if ( $fileinfo->getType() === "dir" ) {
				$retval['folders'][] = [
					'name'    => $fileinfo->getFilename(),
					'type'    => $fileinfo->getType(),
					'path'    => $fileinfo->getPathname(),
					'lastmod' => $fileinfo->getMTime(),
				];
			} else {
				$retval['files'][] = [
					'name'    => $fileinfo->getFilename(),
					'type'    => mime_content_type( $fileinfo->getRealPath() ),
					'ext'     => $fileinfo->getExtension(),
					'path'    => $fileinfo->getPathname(),
					'size'    => $fileinfo->getSize(),
					'lastmod' => $fileinfo->getMTime(),
				];
			}
		}

		return $retval;
	}

	private function createFolder( $dir, $folderName ) {
		$dir = $this->path . '/' . $dir . '/' . $folderName;

		if ( ! is_dir( $dir ) ) {
			return ! mkdir( $dir, 0777, true ) && ! is_dir( $dir ) ? die( 'Failed to create folder' ) : die( 'OK' );
		}

		die( 'OK' );
	}

	private function renameFolder( $currentPath, $oldName, $newName ) {

		$from = $this->path . '/' . $currentPath . '/' . $oldName;
		$to   = $this->path . '/' . $currentPath . '/' . $newName;

		return ! rename( $from, $to ) ? die( 'Failed to rename folder' ) : die( 'OK' );
	}

	private function changeFolder( $newDir ) {
		$dir = $this->path . '/' . $newDir;

		if ( is_dir( $dir ) ) {
			$this->path = $dir;
		}
	}

	private function copyItem( $currentPath, $copyPath, $files ) {
		foreach ( $files as $file ) {
			$from = $this->path . '/' . $currentPath . '/' . $file;
			$to   = $this->path . '/' . $copyPath . '/' . $file;

			if ( is_dir( $from ) ) {
				$this->recurseCopy( $from, $to );
			} else {

				if ( file_exists( $to ) ) {
					$to = $this->path . '/' . $copyPath . '/Copy of ' . $file;
				}

				copy( $from, $to );
			}
		}

		die( 'OK' );
	}

	private function recurseCopy( $from, $to ) {
		$dir = opendir( $from );
		if ( ! mkdir( $to ) && ! is_dir( $to ) ) {
			return die( 'Failed to create folder' );
		}
		while ( false !== ( $file = readdir( $dir ) ) ) {
			if ( ( $file !== '.' ) && ( $file !== '..' ) ) {
				if ( is_dir( $from . '/' . $file ) ) {
					$this->recurseCopy( $from . '/' . $file, $to . '/' . $file );
				} else {
					copy( $from . '/' . $file, $to . '/' . $file );
				}
			}
		}
		closedir( $dir );
	}

	private function moveItem( $currentPath, $movePath, $files ) {
		foreach ( $files as $file ) {
			$from = $this->path . '/' . $currentPath . '/' . $file;
			$to   = $this->path . '/' . $movePath . '/' . $file;

			if ( is_dir( $from ) ) {
				$this->recurseMove( $from, $to );
			} else {
				rename( $from, $to );
			}
		}

		die( 'OK' );
	}

	private function recurseMove( $from, $to ) {
		$dir = opendir( $from );
		if ( ! mkdir( $to ) && ! is_dir( $to ) ) {
			return die( 'Failed to create folder' );
		}
		while ( false !== ( $file = readdir( $dir ) ) ) {
			if ( ( $file !== '.' ) && ( $file !== '..' ) ) {
				if ( is_dir( $from . '/' . $file ) ) {
					$this->recurseMove( $from . '/' . $file, $to . '/' . $file );
				} else {
					rename( $from . '/' . $file, $to . '/' . $file );
				}
			}
		}
		closedir( $dir );

		rmdir( $from );
	}

	private function deleteItem( $currentPath, $files ) {

		foreach ( $files as $file ) {
			$from = $this->path . '/' . $currentPath . '/' . $file;

			if ( is_dir( $from ) ) {
				$this->recurseDelete( $from );
			} else {
				unlink( $from );
			}
		}

		die( 'OK' );
	}

	private function recurseDelete( $from ) {
		$dir = opendir( $from );
		while ( false !== ( $file = readdir( $dir ) ) ) {
			if ( ( $file !== '.' ) && ( $file !== '..' ) ) {
				if ( is_dir( $from . '/' . $file ) ) {
					$this->recurseDelete( $from . '/' . $file );
				} else {
					unlink( $from . '/' . $file );
				}
			}
		}
		closedir( $dir );
		rmdir( $from );
	}

	private function uploadFiles( $uploadPath, $fileItem ) {

		$uploadPath = $this->path . '/' . $uploadPath;
		$filename   = $fileItem['name'];
		$filepath   = $uploadPath . '/' . $filename;

		// validate extension with allowed extensions with regex
		if ( ! preg_match( '/^.*\.(' . $this->allowed_ext . ')$/i', $filename ) ) {
			// make an array from regex like allowed extensions and if there is ? in the extension, remove the ? and character before it and add it to the array
			$allowed_ext = explode( '|', $this->allowed_ext );
			foreach ( $allowed_ext as $key => $value ) {
				$allowed_ext[$key] = str_replace( '?', '', $value );

				// if there is a ? in the extension, remove ? mark and the character before it
				if ( strpos( $value, '?' ) !== false ) {
					$allowed_ext[] = str_replace( substr( $value, strpos( $value, '?' ) - 1, strpos( $value, '?' ) - 1 ), '', $value );
				}
			}
			asort($allowed_ext);
			// implode the array to string and return error
			return die( 'Invalid file extension. Allowed extensions are: ' . implode( ', ', $allowed_ext ) );

		}

		// validate file size (in mega bytes)
		if ($this->max_file_size && $fileItem['size'] > $this->max_file_size * 1024 * 1024 ) {
			return die( 'File size is too big. Maximum file size is: ' . $this->max_file_size . 'MB' );
		}

		if ( move_uploaded_file( $fileItem['tmp_name'], $filepath ) ) {
			die( 'OK' );
		}

		die( 'Failed to upload file' );
	}

	private function downloadFile( $path ) {
		$filename = basename( $path );
		$filepath = $this->path . '/' . $path;

		if ( ! empty( $filename ) && file_exists( $filepath ) ) {
			header( 'Cache-Control: public' );
			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: application/octet-stream' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Content-Length: ' . filesize( $filepath ) );
			readfile( $filepath );
			exit;
		}
	}

	private function recursiveDotCheck( $req ) {

		foreach ( $req as $item ) {
			if ( is_array( $item ) ) {
				$this->recursiveDotCheck( $item );
			} else {
				if ( strpos( $item, '../' ) ) {
					die( 'Access Denied' );
				}
			}
		}
	}

	private function renderHtml( $files ) {
		$dirToScan = isset( $_GET['chdir'] ) ? $_GET['chdir'] : '/';

		$dirs   = array_filter( explode( '/', $dirToScan ) );
		$accume = '';
		$i      = 0;

		$breadcrumbs = [];

		foreach ( $dirs as $dir ) {
			$accume                    .= $dir . '/';
			$breadcrumbs[ $i ]['name'] = $dir;
			$breadcrumbs[ $i ]['path'] = $accume;
			$i ++;
		}

		$totalBreadcrumb = count( $breadcrumbs );

		// index counter
		$counter = 0;

		if ( ! isset( $_GET["m"] ) ) { ?>

            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8"/>
                <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                <title>File Manager</title>
                <link rel="preconnect" href="https://fonts.googleapis.com"/>
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
                <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
                <link rel="stylesheet" href="<?= APP_URL ?>/public/filebrowser/style.css" />
            </head>

            <body>
            <div class="wrapper flex flex-col h-screen">
            <div class="flex h-full">
            <div class="left-column">
                <div class="flex items-center justify-around divide-x layout-btns">
                    <button class="p-2 w-50 flex items-center justify-center selected-bg" title="List layout" data-target-layout="list">
                        <i class="fa fa-th-list"></i>
                        <small>List</small>
                    </button>
                    <button class="p-2 w-50 flex items-center justify-center" title="Grid layout" data-target-layout="grid">
                        <i class="fa fa-th"></i>
                        <small>Grid</small>
                    </button>
                </div>
                <div class="menu-items mt-3">
                    <a href="javascript:void" class="d-block">
                        <label for="fileinput" class="flex items-center py-2" title="Upload File">
                            <input type="file" id="fileinput" class="d-none" multiple=""/>
                            <span style="width: 24px"></span>
                            <i class="fa fa-upload pr-5"></i>
                            <span class="menu-items__title">Upload File</span>
                        </label>
                    </a>
                    <a href="javascript:createFolder()" class="d-block" title="New Folder">
                        <div class="flex items-center py-2">
                            <span style="width: 24px"></span>
                            <i class="fa fa-folder pr-5"></i>
                            <span class="menu-items__title">Create Folder</span>
                        </div>
                    </a>
                </div>

            </div>

            <div class="right-column flex-auto" data-layout="list">
		<?php } ?>
        <div class="right-column_header flex items-center justify-between border border-b">
            <ol class="breadcrumb flex" id="breadcrumb">

                <li class="flex items-center">
                    <a href="javascript:void(0)" data-filename="/">Home</a>
					<?php if ( $totalBreadcrumb ) : ?>
                        <i class="fa fa-angle-right mx-3"></i>
					<?php endif; ?>
                </li>

				<?php foreach ( $breadcrumbs as $key => $item ) : ?>
                    <li class="flex items-center">
                        <a href="javascript:void(0)" data-filename="<?= $item['path'] ?>"><?= $item['name'] ?></a>
						<?php if ( $key < $totalBreadcrumb - 1 ) : ?>
                            <i class="fa fa-angle-right mx-3"></i>
						<?php endif; ?>
                    </li>
				<?php endforeach; ?>

            </ol>

        </div>

        <div class="content-flies">
            <div class="files-table">
                <div class="flex files-table_head pl-4 pt-2 border border-b">
                    <div class="files-table_head-item py-2.5" data-column-field="7">
                        Name
                    </div>
                    <div class="files-table_head-item py-2.5" data-column-field="3">
                        Last modified
                    </div>
                    <div class="files-table_head-item py-2.5" data-column-field="2">
                        File size
                    </div>
                </div>
                <div class="files-table_body">

                    <!-- check if directory is empty -->
					<?php if ( empty( $files['folders'] ) && empty( $files['files'] ) ) : ?>
                        <div class="flex items-center" style="gap: 10px; padding: 20px;">
                            <span>This directory is empty.</span>
                        </div>
					<?php endif; ?>

                    <!-- Folder Items -->
                    <div class="folder-items">
						<?php foreach ( $files['folders'] as $item ) : ?>
                            <div class="file-item border border-b" data-item-type="folder" data-filename="<?= $item['name'] ?>" data-index="<?= $counter ?>">
                                <div class="file-item_contents flex">
                                    <div class="file-item_name flex items-center" data-column-field="7">
														<span class="file-item_icon">
															<i class="fa fa-folder"></i>
														</span>
                                        <a href="javascript:void(0)"><?= $item['name'] ?></a>
                                    </div>
                                    <div class="file-item_date flex items-center" data-column-field="3">
										<?= date( 'M d, Y', $item['lastmod'] ) ?>
                                    </div>
                                    <div class="file-item_size flex items-center" data-column-field="2">
                                        —
                                    </div>
                                </div>
                            </div>
							<?php $counter ++;
						endforeach; ?>

                    </div>

                    <!-- File Items -->
                    <div class="file-items">

						<?php foreach ( $files['files'] as $item ) : ?>

							<?php
							$img    = NULL;
							$icon   = NULL;
							$styles = '';

							if (strpos($item['type'], 'image') === 0) {
								$img = 'data:image/png;base64,' . base64_encode(file_get_contents($item['path']));
							} else {
								$styles = 'background-size:70px 70px;';
							}

							if (file_exists(__DIR__ . '/icons/' . strtolower($item['ext']) . '.png')) {
								$icon = APP_URL . '/public/filebrowser/icons/' . strtolower($item['ext']) . '.png';
							} else {
								$icon = APP_URL . '/public/filebrowser/icons/unknown.png';
							}
							?>

                            <div class="file-item border border-b" data-item-type="file" data-filename="<?= $item['name'] ?>" data-index="<?= $counter ?>">
                                <div class="file-item_thumb" style="<?= $styles ?> background-image: url('<?= $img ?? $icon ?>');"></div>

                                <div class="file-item_contents flex">
                                    <div class="file-item_name flex items-center" data-column-field="7">
														<span class="file-item_icon">
															<img class="d-block" src="<?= $icon ?>" alt="icon" loading="lazy"/>
														</span>

                                        <a href="javascript:void(0)"><?= $item['name'] ?></a>
                                    </div>
                                    <div class="file-item_date flex items-center" data-column-field="3">
										<?= date( 'M d, Y', $item['lastmod'] ) ?>
                                    </div>
                                    <div class="file-item_size flex items-center" data-column-field="2">
										<?= $this->formatBytes( $item['size'] ) ?>
                                    </div>
                                </div>
                            </div>
							<?php $counter ++;
						endforeach; ?>

                    </div>

                </div>
            </div>
        </div>

		<?php if ( ! isset( $_GET["m"] ) ) { ?>
            </div>
            </div>
            </div>

            <!--context menu-->

            <div id="context-menu" class="">
                <ul class="py-2">
                    <li class="menu-download">
                        <a href="javascript:download()" class="flex items-center px-4 py-2">
                            <i class="fa fa-download mr-3"></i>
                            <span>Download</span>
                        </a>
                    </li>
                    <li class="menu-copy">
                        <a href="javascript:copy()" class="flex items-center px-4 py-2">
                            <i class="fa fa-clone mr-3"></i>
                            <span>Copy</span>
                        </a>
                    </li>
                    <li class="menu-move">
                        <a href="javascript:move()" class="flex items-center px-4 py-2">
                            <i class="fa fa-arrows mr-3"></i>
                            <span>Move</span>
                        </a>
                    </li>
                    <li class="menu-rename">
                        <a href="javascript:rename()" class="flex items-center px-4 py-2">
                            <i class="fa fa-pencil mr-3"></i>
                            <span>Rename</span>
                        </a>
                    </li>
                    <li class="menu-delete">
                        <a href="javascript:deleteItem()" class="flex items-center px-4 py-2">
                            <i class="fa fa-trash-o mr-3"></i>
                            <span>Delete</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- upload boxes -->

            <div id="upload-progress-boxes">
                <div class="upload-box-header d-none">
                    <div class="flex justify-between">
                        <span>Uploads</span>
                        <div>
                            <a href="javascript:toggleUploadBox()" class="upload-box-angle" title="Minimize/Maximize"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
                            <a href="javascript:closeUploadBox()" class="upload-box-close" title="Close"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
                <div class="upload-box-body"></div>
            </div>

            <!--modal-->
            <div id="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modal Title</h5>
                            <button type="button" class="btn-close" data-close="modal">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="modal-body"></div>
                        <div class="modal-footer">
                            <button type="button" class="" data-close="modal">CANCEL</button>
                            <button type="button" class="btn-ok" data-confirm-btn>OK</button>
                        </div>
                    </div>
                </div>
            </div>

            <a href="#" id="download-link"></a>
            <script>
                // todo current dir thik korte hobe
                let CURRENT_DIR = "/";
            </script>

            <script src="<?= APP_URL ?>/public/filebrowser/script.js"></script>
            </body>

            </html>

		<?php }
	}
}

class FileExtFilter extends FilterIterator {

	private $fileExt;

	public function __construct( $iterator, $fileExt ) {
		parent::__construct( $iterator );
		$this->fileExt = $fileExt;
	}

	public function accept(): bool {
		$file = $this->getInnerIterator()->current();

		return $file->getType() === 'dir' || preg_match( "/\.({$this->fileExt})$/i", $file );
	}
}
