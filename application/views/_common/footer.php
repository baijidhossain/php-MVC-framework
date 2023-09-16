<footer class="main-footer">
  <div class="pull-right hidden-xs">
  </div>
  <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="<?= APP_URL ?>" target="blank"><?= SITE_NAME ?></a>.</strong> All rights reserved.
</footer>

<!-- /.ending -wrapper -->
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">

    <div class="modal-content">

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<!-- jQuery 2.1.4 -->
<script src="<?= APP_URL ?>/public/js/jquery.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="<?= APP_URL ?>/public/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?= APP_URL ?>/public/js/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?= APP_URL ?>/public/js/fastclick.min.js"></script>
<!-- Select2 -->
<script src="<?= APP_URL ?>/public/js/select2.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= APP_URL ?>/public/js/app.min.js"></script>
<!-- ckeditor.js -->
<script src="<?= APP_URL ?>/public/js/ckeditor.js"></script>
<script>
  $(document).ready(function() {
    $(document).on('hidden.bs.modal', function(e) {
      $(e.target).removeData('bs.modal');
      $('.modal-content').html('');
      $('[data-toggle="tooltip"]').tooltip();
    });

    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }

  });
</script>
<script>


  let cPath = location.pathname;
    $('a[href="' + cPath + '"]').parents('li').addClass('active');
</script>



</body>

</html>