<?php
error_reporting(0);
header("Content-Type: application/json");
header("Acess-Control-Allow-Origin: *");
header("Acess-Control-Allow-Methods: POST");
header("Acess-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Methods, Authorization");

include '../config.php'; // include database connection file

$data = json_decode(file_get_contents("php://input"), true); // collect input parameters and convert into readable format
	// print_r("in".$data);
$fileName  =  $_FILES['file']['name'];
$tempPath  =  $_FILES['file']['tmp_name'];
$fileSize  =  $_FILES['file']['size'];
		
// echo $fileName ;
// echo $tempPath ;
// echo $fileSize ;

if(empty($fileName) || !isset($_POST['Id']) || empty($_POST['Id']))
{
	$errorMSG = json_encode(array("message" => $fileName.$_POST['Id'], "status" => false));	
	echo $errorMSG;
}
else
{
    $Id = $_POST['Id'];
	
	$base_path = 'http://localhost/xms/src/uploads/';
	$upload_path = '../uploads/'; // set upload folder path 

	// echo "OUT";
	if(!is_dir($base_path.$Id)){
		mkdir($base_path.$Id, 0777, true);
		$folder_path = $base_path.$Id;
		
	}


	// $basepath = 'http://localhost/xms/src/uploads/';
	$fileExt = strtolower(pathinfo($fileName,PATHINFO_EXTENSION)); // get image extension
		
	// valid image extensions
	$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'pdf', 'mov'); 
					
	// allow valid image file formats
	if(in_array($fileExt, $valid_extensions))
	{				
		//check file not exist our upload folder path
		if(!file_exists($upload_path . $fileName))
		{
			// check file size '5MB'
			if($fileSize < 5000000){
				move_uploaded_file($tempPath, $upload_path . $fileName); // move file from system temporary path to our upload folder path 
                $url = $folder_path.'/'.$fileName;
                // echo $url;
			}
			else{		
				$errorMSG = json_encode(array("message" => "Sorry, your file is too large, please upload 5 MB size", "status" => false));	
				echo $errorMSG;
			}
		}
		else
		{		
			$errorMSG = json_encode(array("message" => "Sorry, file already exists check upload folder", "status" => false));	
			echo $errorMSG;
		}
        
	}
	else
	{		
		$errorMSG = json_encode(array("message" => "Sorry, only JPG, JPEG, PNG, PDF, MOV & GIF files are allowed", "status" => false));	
		echo $errorMSG;		
	}

    // if(!isset($errorMSG)){
    //     $successMSG = json_encode(array("message" => "Successfull", "status" => true));	
    //     echo $successMSG;	 
    // }
}
		
// if no error caused, continue ....
if(!isset($errorMSG))
{
    $sql = 'INSERT INTO clientfiles(Id, FileUrl, FileName) VALUES (?,?,?)';
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("iss", $Id, $url, $fileName);
        if($stmt -> execute()){
            echo json_encode(array("message" => $url, "status" => true));	
        }else{
            return $options = "Fail".mysqli_error($mysqli);
        }
    }else{
        return $options = "Fail".mysqli_error($mysqli);
    }
			
	
}

?>