<?php
require_once "../config.php";
require __DIR__ . "/vendor/autoload.php";
//def("DOMPDF_ENABLE_REMOTE", true);

$files = glob("./pdf/include/*.php");
foreach($files as $file) include_once($file);

//$html = '<img src = __DIR__ . ../assets/lion.jpg>';

use Dompdf\Dompdf;
use Dompdf\Options;

// $html='<img src="../assets/logo.png">';

$json=file_get_contents('php://input');
//$Id = json_decode($json,true)['Id'];
$decode_array = json_decode($json,true);

$userArrayResponse = false;
$resArrayResponse = false;
$var='';

//$pdfpath=getcwd();
//$pdfpath=(__DIR__);
//$pdfpath="http://localhost/xms/src/api/pdf_filename_".$Email.".pdf";
//(__FILE__)



if(isset($_POST['Id']) && !empty($_POST['Id'])){
   $sql_userDetails = 'SELECT  Name, Email, SalesCode, Phone, Organization, SubAgency FROM login WHERE Id = ?';
   $sql_userResponse = 'SELECT mq.Question, rs.Response, mq.Requirement FROM responses as rs JOIN masterquestions as mq on mq.QuestionId = rs.QuestionId WHERE rs.Id = ?';
//    $sql_userResponse = 'SELECT mq.Question, rs.Response FROM responses as rs JOIN masterquestions as mq on mq.QuestionId = rs.QuestionId WHERE rs.Id = ?';
   
   
    if($stmt = $mysqli->prepare($sql_userDetails)){
        
        $Id = $_POST['Id'];
        $stmt->bind_param("i", $Id);
        if($stmt -> execute()){
            //store resultsin
            $stmt->store_result();
            $stmt -> bind_result($Name, $Email, $SalesCode, $Phone, $Organization, $SubAgency);
            $userArr = array();
            
            if($stmt -> num_rows()>0){
                while($stmt -> fetch()) {
                $temp = array("Name"=>$Name, "Email"=>$Email, "SalesCode"=>$SalesCode, "Phone"=>$Phone, "Organization"=>$Organization, "SubAgency"=>$SubAgency);
                array_push($userArr, $temp);
                }
                 $userArrayResponse = true;
                //  print_r ($userArr);
                
            }else
            {
                // response($userArr);
                $userArrayResponse = false;
            }
        }else{
            // response("False".mysqli_error($mysqli));
            $userArrayResponse = ("False".mysqli_error($mysqli));
        }
        
    }else{
      response("invaild id");
    }


    if($stmt = $mysqli->prepare($sql_userResponse)){

    $stmt->bind_param("i", $Id);
    if($stmt -> execute()){
        //store results
        $stmt->store_result();
        $stmt -> bind_result($Question, $Response, $Requirement);
        // $stmt -> bind_result($Question, $Response);
        $resArr = array();
                
            if($stmt -> num_rows()>0){
                while($stmt -> fetch()){
                        
                    // $temp = array("Question"=>$Question, "Response"=>$Response);
                    $temp = array("Question"=>$Question, "Response"=>$Response, "Requirement"=>$Requirement);
                    array_push($resArr, $temp);
                }
                    $resArrayResponse = true;
                    //print_r($resArr);
            }else{
            //response($resArr);
            $resArrayResponse = false;
            }
            
        }else{
            // response("False".mysqli_error($mysqli));
            $resArrayResponse = ("False".mysqli_error($mysqli));
        }
            //close statement
            $stmt->close();
    }else{
        // response("False".mysqli_error($mysqli));
        // response("invaild");
        
        $resArrayResponse = "Incorrect parameters";
    }

    if($userArrayResponse==true && $resArrayResponse==true){

    /**
     * Set the Dompdf options
     */

    // $options = new Options;
  
    $dompdf = new Dompdf(
        ["chroot"=>__DIR__]
    );
    /**
     * Set the paper size and orientation
     */
    $dompdf->setPaper("A4", "landscape");
    /**
     * Load the HTML and replace placeholders with values from the form
     */
    $html = file_get_contents("../template.html");

    // $html = str_replace(["{{ Name }}", "{{ Email }}", "{{ SalesCode }}", "{{ Phone }}", "{{ Organization }}", "{{ SubAgency }}"], [$Name, $Email, $SalesCode, $Phone, $Organization, $SubAgency], $html);
    $html = str_replace(["{{ Name }}", "{{ Email }}", "{{ SalesCode }}", "{{ Phone }}", "{{ Organization }}", "{{ SubAgency }}", "{{ Requirement }}"], [$Name, $Email, $SalesCode, $Phone, $Organization, $SubAgency,$Requirement], $html);

        $table="";
        for($i=0;$i<count($resArr);$i++){
        //print_r($resArr);
        $table=$table.'<tr><td style="text-align: left">'.$resArr[$i]["Question"].'</td>';
        $table=$table.'<td style="text-align: middle">'.$resArr[$i]["Response"].'</td>';
        // $table=$table.'<td style="text-align: right">'.$resArr[$i]["Requirement"].'</td></tr>';
        
        }
            
        
        $html = str_replace(["{{ Table }}"], [$table], $html);

     
      
    

    // /**
    //  * Create the PDF and set attributes
    //  */
    // $dompdf->render();

    
        
        $dompdf = new DOMPDF();
        // $path = "http://localhost/xms/src";
        //$dompdf->load_html($aData[$html]);
        $dompdf->load_html($html);

       // $dompdf->set_option('isRemoteEnabled',TRUE);

        $dompdf->render();
        //print the pdf file to the screen for saving
        //$dompdf->stream("pdf_filename_".$Name.".pdf", array("Attachment" => false));
     //save the pdf file
     $output = $dompdf->output();
       file_put_contents($Id."_".$Name.".pdf", $dompdf->output());
        // $output = $dompdf->output();
        // file_put_contents('file.pdf', $output);
    // /**
    //  * Send the PDF to the browser
    //  */
       //$dompdf->stream(file_put_contents, ["Attachment" => 0]);

    // /**
    //  * Save the PDF file locally
    //  */
    //$output = $dompdf->output();
    //file_put_contents("file.pdf", $output);

    //  if(empty($resArr)){
    //  echo 'no data';
    //  }

    //  }else{
    //     echo 'invaild';
    
    //  }
   
      $pdfpath="http://localhost/xms/src/api/$Id-$Name.pdf";
    
    response($pdfpath); 
    }else{
        echo $userArrayResponse;
        if($userArrayResponse == false) 
            $var1 = "user not exist";
        if($resArrayResponse == false)
            $var2 = "no response";
        response($var1);
        response($var2);
    }
}else{
    response("Invalid parameters for pdf");
}
function response($signoffMessage)
{
$response['res'] = $signoffMessage;
$json_response = json_encode($response);
echo $json_response;
}

?>