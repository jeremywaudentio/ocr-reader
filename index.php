<?php 
error_reporting(0);

function upload_file_array($file) { 
  $image =$_FILES[$file]["name"];
  $uploadedfile = $_FILES[$file]['tmp_name'];
  $uploaded_type = $_FILES[$file]['type'];
 
  if ($image) {
    $filename = stripslashes($_FILES[$file]['name']);
    $getext = pathinfo($filename);
    $extension = strtolower($getext['extension']); 
    $hashedname = "ocr";
    $hashedname_ext = $hashedname. '.'. $extension;
    $size=filesize($_FILES[$file]['tmp_name']);
 
    if (move_uploaded_file($_FILES[$file]['tmp_name'], "/tmp/".$hashedname. '-0.'. $extension)) {
      if ($extension == "pdf") { // if pdf convert to JPG
        chmod('/tmp/ocr-0.pdf', 0777);
        $command = "/usr/local/bin/convert -density 600 tmp/ocr-0.pdf tmp/ocr-0.jpg 2>&1";
        echo exec($command, $output); $extension = "jpg";
      }

      $command = "/usr/local/bin/tesseract /tmp/ocr-0.$extension ocr 2>&1";
      echo exec($command, $output);

        //print_r($output);
    }
  }
    //If no errors registred, print the success message
}


if ($_POST['submit']) { 
  $tmp = $_FILES['image']['tmp_name'];
  if ($tmp) { 
    $image = upload_file_array("image");
  }
}

?>
<!doctype html>
<html>
<head>
  <meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0, user-scalable=yes">
  <title>OCR</title>

  <!--Only used by the demo to add common bootstrap button styles to the file chooser-->
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    
  <style>
  .pre {
    white-space: pre; 
    unicode-bidi: embed;
  }

  body { width: 850px; margin: 0 auto;}
  </style>
</head>

<body unresolved>

    <h1>OCR Tester</h1>
        <form action="" method="POST" enctype="multipart/form-data">
          <input type="file" name="image" /><br /><br />
          <input type="submit" name="submit" />
        </form>

        <h2>OCR Text</h2>
        <?php 
          $codes = fopen("ocr.txt", "r");
          if ($codes) {
            while (($line = fgets($codes)) !== false) {
            
              echo $code[] = $line;
              echo "<br />";
                
            }
            fclose($codes);
          } else {
            echo "Upload File to View";
          } 
        ?>
</body>
</html>