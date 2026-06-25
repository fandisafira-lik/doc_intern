<?php
    session_start();

    if(!isset($_SESSION['doc']['username']))
    {
    	echo "
		<script>
			// alert('Silahkan Login');
			window.location.href = 'login.php';
		</script>
    	";
    }
          else
              {
          	    include('header.php');

              		if(empty($_GET['mod'])) {
              			include_once "mod/upload/index.php";
              		}
                  		else
                      		{
                      			$file = $_GET["cmd"];
                      			$includeFile = "mod/".$_GET['mod']. "/" . $file.'.php';
                            $includeFile2 = dirname(__FILE__) ."\home.php";
                            // $includeFile2 = "mod/".$_GET['mod']. "/" . $file2.'.php';
                      			if (file_exists($includeFile))
                          			{
                                      // echo "$includeFile2";
                      				        include_once($includeFile);
                      			   }
                          			else
                                			{
                                            include_once($includeFile2);
                                			}
		}

	    include('footer.php');
    }
?>
