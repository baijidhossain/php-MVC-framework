<?php
class AUTH{
	
	public static function checkPermission($section, $controller, $action){
		
		$db = new Database;
		
		$request = $section . $controller . '/' . $action;
		
		$check_entity = $db->query("SELECT * FROM permission WHERE action = ?", $request);
		
		if($check_entity->numRows() > 0){
			
			if(!isset($_SESSION['login']))
			{
				Util::redirect("/account/login");
				
			}else{
				
				$permission = $check_entity->fetchArray();
				
				$check_permission = $db->query("SELECT * FROM acl WHERE permission_id = ? AND group_id = ?", $permission['id'], $_SESSION['groupid']);
			
				if($check_permission->numRows() < 1){
					
					return false;
				}
			}
			
		}
		
		$db->close();
		
		return true;
	}
	
	public static function loggedin(){
		
		if(isset($_SESSION['login'])){
			
			return true;
		}
		
		return false;
		
	}
}