<?php
  // This script should run at the beginning of every
  // php file that requires a session
  function custom_start(){
    // lifetime of session lasts for 24 hours to
    // prevent user from unexpectantly closing session
    ini_set('session.gc_maxlifetime', 24*3600);
    ini_set('session.cache_expire', 24*3600);
    session_set_cookie_params(24*3600);
    // Change session path to prevent it from being deleted from standard temp folder
    ini_set('session.save_path', '/tmp_amd/adams/export/adams/2/z5020926/public_html/binflabs/sessions');
    session_start();
  }

  // Useful when needing to print all session data
  function print_session(){
      echo '<pre>';
      var_dump($_SESSION);
      echo '</pre>';
  }
?>
