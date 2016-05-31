<?
/*
+--------------------------------------------------------
| Class For Sending Mail With Atachments
|================================
| Author: tRiNEX (c) 2005
| Web: http://trinex.ru
| E-mail: admin@trinex.ru, trin@phpinfo.ru
| Date: 03.04.2005
| FileName: email.class.php
| Version: 1.1
+---------------------------------------------------------

>  ����� ��� �������� ����� � ����������.


*/


Class Email
{
        //���������:
        var $EmailCharset = 'windows-1251';

        //������ ����������� ������:
        var $Emails = array('');

        //�����������:
        var $EmailFrom = '';

        //��� ������:
        var $EmailType = 'text/plain';

        //�������������� ���������:
        var $EmailXmailer = 'TRX Mailer';

        //���� ������:
        var $EmailSubject = 'Mail From TRX Mailer';

        //E-mail ��� ������
        var $EmailErrorTo = 'igorgr@mail.ru';

        //�������������� ����������:
        var $EmailReplyTo;

        // ����� ������:
        var $EmailMessage = 'Read the message and check the file!';

        //��� ��� �� �����:
        var $EmailBody;
        var $EmailTo;

        //������ ������:
        var $EmailFiles;

        //���������, ��� ��� ���� �� �����:
        var $Headers;

        //������:
        var $EmailError = 0;
        var $EmailErrors = '';



        //����� ��� ���������� ���� ������
        function BuildMessage()
        {
                //���������� �����:
                $un_bound = "TRX".time();

                //���������, ������������ ����� �����������:
                $this->Headers .= "From: ".$this->EmailFrom."\n";

                //���������, ������������ ���� ������:
                $this->Headers .= "Subject: ".$this->EmailSubject."\n";

                //�������� ���������� EmailErrorTo � ���������� ��������� Errors-To:
                if($this->EmailErrorTo != ''){
                        $this->Headers .= "Errors-To: ".$this->EmailErrorTo."\n";
                }

                //��� ��������� ����������:
                $this->Headers .= "X-Mailer: ".$this->EmailXmailer."\n";
                 //MIME 1.0:
                $this->Headers .= "MIME-Version: 1.0\n";
                //��������� ��� ������:
                $this->Headers .= "Content-Type: multipart/mixed; boundary=".$un_bound."\n\n";

                //�������� ����� ������:
                $this->EmailBody  = "--".$un_bound."\n";
                //����������� ���/������ ������ � ��� ���������:
                $this->EmailBody .= "Content-Type: ".$this->EmailType."; charset=".$this->EmailCharset."\n";
                //� ����� ��� �����������:
                $this->EmailBody .= "Content-Transfer-Encoding: 8bit\n\n";
                //������������ ���� ������:
                $this->EmailBody .= $this->EmailMessage;

                //������ ������:
                if(count($this->EmailFiles) > 0){
                    //���������� ������:
                        for($i=0;$i<count($this->EmailFiles);$i++){
                                $rfile = $this->EmailFiles[$i];
                                //������� ������� ����:
                                if(!($fd = fopen($rfile, "r"))){

                                    //������ �������� (���� �� ������? ���������� ��� ������?):
                                    $this->EmailErrors .= "Failed to open ".$this->EmailFiles[$i]."!\n";
                                    $this->EmailError = 1;
                                    //���������� ������� �����:
                                    continue(1);
                                 }

                                //������ ����, ������������ � BASE64 � ����� ������ �� �����:
                                $text = chunk_split(base64_encode(fread($fd, filesize($rfile))));
                                //���������� �����:
                                $this->EmailBody .= "\n--".$un_bound."\n";
                                //��������� � ���, ��� ������ ���� ����:
                                $this->EmailBody .= "Content-Type: application/octet-stream;\n";
                                //� �� ��������� � ��������� BASE64:
                                $this->EmailBody .= "Content-Transfer-Encoding: base64\n";
                                //� ����� ��, ��� �� - ����� � ��� ���:
                                $this->EmailBody .= "Content-Disposition: attachment; filename = ".basename($rfile)."\n\n";
                                //�� � ��� ����:
                                $this->EmailBody .= $text;
                        }
                }

                //��������� ���� ������ ���������� ������:
                $this->EmailBody .= "\n--".$un_bound."--\n";

        }

        //����� �������� ������:
        function SendEmail()
        {
                //�������� ������ �� ��������� :) � ���������� ������� ������. � ������ ������ ��������� FALSE:
                for($i=0; $i < count($this->Emails);$i++){
                        $this->EmailTo = $this->Emails[$i];
                        if(!mail($this->EmailTo, $this->EmailSubject, $this->EmailBody, $this->Headers)){
                                $sent = FALSE;
                                $this->EmailErrors .='Failed to send mail to '.$this->EmailTo.'\n';
                                $this->EmailError = 1;
                        }else{
                                $sent = TRUE;
                        }
                }
                return $sent;
        }

}





// ************************************************************************* 
// 
//   � �������� ������ �p���������� html-������ (����������� �������������). 
//   ������ ����� - ��������� ���� �� ��������. 
//   ��� ��� �������� ��� ��, ��� �������� ����: 
// 
// ************************************************************************* 


//  $mail=new html_mime_mail(); 
//  $mail->add_html("<html><body><center><h2>�p����!<br><br>". 
//                  "<br>������� �������� ���� [/bin/ls] ...". 
//                  "</h2></center></body></html>"); 
//  $mail->add_attachment("/bin/","ls"); 
//  $mail->build_message('win'); // ���� �� "win", �� ����p���� koi8 
//  $mail->send('��������_����_������_����������', 
//              '����_(E-MAIL)', 
//              '��_����_(E-MAIL)', 
//              '���� ������');


class html_mime_mail { 
  var $headers;  
  var $multipart;  
  var $mime;  
  var $html;  
  var $parts = array();  

function html_mime_mail($headers="") {  
    $this->headers=$headers;  
}  

function add_html($html="") {  
    $this->html.=$html;  
}  

function build_html($orig_boundary,$kod) {  
    $this->multipart.="--$orig_boundary\n";  
    if ($kod=='w' || $kod=='win' || $kod=='windows-1251') $kod='windows-1251'; 
    else $kod='koi8-r'; 
    $this->multipart.="Content-Type: text/html; charset=$kod\n";  
    //$this->multipart.="BCC: del@ipo.spb.ru\n"; 
    $this->multipart.="Content-Transfer-Encoding: Quot-Printed\n\n";  
    $this->multipart.="$this->html\n\n";  
}  

function add_attachment($path="", $name = "", $c_type="application/octet-stream") {  
    if (!file_exists($path.$name)) { 
      print "File $path.$name dosn't exist."; 
      return; 
    } 
    $fp=fopen($path.$name,"r"); 
    if (!$fp) { 
      print "File $path.$name coudn't be read."; 
      return; 
    }  
    $file=fread($fp, filesize($path.$name)); 
    fclose($fp); 
    $this->parts[]=array("body"=>$file, "name"=>$name,"c_type"=>$c_type);  
}  

function build_part($i) {  
    $message_part="";  
    $message_part.="Content-Type: ".$this->parts[$i]["c_type"];  
    if ($this->parts[$i]["name"]!="")  
       $message_part.="; name = \"".$this->parts[$i]["name"]."\"\n";  
    else  
       $message_part.="\n";  
    $message_part.="Content-Transfer-Encoding: base64\n";  
    $message_part.="Content-Disposition: attachment; filename = \"". 
       $this->parts[$i]["name"]."\"\n\n";  
    $message_part.=chunk_split(base64_encode($this->parts[$i]["body"]))."\n"; 
    return $message_part;  
}  

function build_message($kod) {  
    $boundary="=_".md5(uniqid(time()));  
    $this->headers.="MIME-Version: 1.0\n";  
    $this->headers.="Content-Type: multipart/mixed; boundary=\"$boundary\"\n";  
    $this->multipart="";  
    $this->multipart.="This is a MIME encoded message.\n\n";  
    $this->build_html($boundary,$kod);  
    for ($i=(count($this->parts)-1); $i>=0; $i--) 
      $this->multipart.="--$boundary\n".$this->build_part($i);  
    $this->mime = "$this->multipart--$boundary--\n";  
}  

function send($server, $to, $from, $subject="", $headers="") {  

    $headers="To: $to\nFrom: $from\nSubject: $subject\nX-Mailer: The Mouse!\n$headers"; 
    $fp = fsockopen($server, 25, &$errno, &$errstr, 30); 
    if (!$fp) 
       die("Server $server. Connection failed: $errno, $errstr"); 
    //echo "sdfgsdgsdgsd".$fp;
    fputs($fp,"HELO $server\n"); 
    fputs($fp,"MAIL FROM: $from\n"); 
    fputs($fp,"RCPT TO: $to\n"); 
    fputs($fp,"DATA\n"); 
    fputs($fp,$this->headers); 
    if (strlen($headers)) 
      fputs($fp,"$headers\n"); 
    fputs($fp,$this->mime); 
    fputs($fp,"\n.\nQUIT\n"); 
    while(!feof($fp)) 
      @$resp.=fgets($fp,1024); 
    fclose($fp); 
  }  
}

// 
// ************************************************************************* 
// ������� ������� �������� �� �����
//
// ************************************************************************* 

# ����� �� �������� �� �������� �����, ����� ����������� � ������ ����
function user_mail($email_to, $email_from, $them, $content, $file="", $path="") {
	if ($file=="") {
		if (mail($email_to, $them, $content, "From:".$email_from."\r\nReply-to:".$email_from."\r\n")) return true; 
		else return false;
	}
	else {
		if (file_exists($path.$file)) {
			if (sent_file($email_to, $them, $content, $file, $path, $email_from)) return true; 
			else return false;}
		else {
			if (mail($email_to, $them, $content, "From:".$email_from."\r\nReply-to:".$email_from."\r\n")) 
				return true; 
			else 
				return false;
		}
	}
}

# �������� ����� �� �����
function sent_file($email_to, $them, $body, $file, $path, $email_from="") {
	
	if ($email_from!="") $mime="From:".$email_from."\nReply-to:".$email_from."\n"; else $mime="";
	$boundary=md5(uniqid(time()));
	$fd=fopen($path.$file, "r");
	$data=fread($fd, filesize($path.$file));
	fclose($fd);
	
	$mime.="MIME-Version: 1.0\n
				Content-Type: multipart/mixed; boundary=\"----------=_$boundary\"\n\n
				----------=_".$boundary."\n
				Content-Type: image/".substr($file,-3)."\n
				Content-Disposition: attachment;\nfilename=".$file."\n
				Content-Transfer-Encoding: base64\n\n
				".chunk_split(base64_encode($data))."
				----------=_".$boundary."\n
				
				";
	if (mail($email_to, $them, $body, $mime)) return true; else return false; }



?>