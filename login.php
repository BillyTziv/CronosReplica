<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1253">
	<link rel="stylesheet" type="text/css" href="style.css" />
</head> 
<body>
	<div id="main">
		<div id="content">
			<?php
				$username = $_POST["username"];
				$password = $_POST["password"];
				
				$url_login = 'https://cronos.cc.uoi.gr/unistudent/login.asp';
	
				$login = curl_init();
				curl_setopt($login, CURLOPT_URL, $url_login);
				curl_setopt($login, CURLOPT_HEADER, false);			// να μην επιστρέφεται το header
				
				curl_setopt($login, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($login, CURLOPT_SSL_VERIFYHOST, false);
				
				curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt"); 
				curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt"); 
				curl_setopt($login, CURLOPT_COOKIESESSION, true); 		//ksekiname neo session
				
				curl_setopt($login, CURLOPT_RETURNTRANSFER, true);		// μας επιστρέφει την σελίδα σε μεταβλητή
				curl_setopt($login, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:12.0) Gecko/20100101 Firefox/12.0");
				curl_setopt($login, CURLOPT_FOLLOWLOCATION, true);		// ακολοθούμε όλα τα redirects
				curl_setopt($login, CURLOPT_AUTOREFERER, true);
		
				$httppage = curl_exec($login);					// ανοίγουμε το λινκ
		
				curl_setopt($login, CURLOPT_POST, true);			// εισαγωγή στοιχείων πρόσβασης
				curl_setopt($login, CURLOPT_POSTFIELDS, 'userName='.$username.'&pwd='.$password.'&submit1=%C5%DF%F3%EF%E4%EF%F2&loginTrue=login');
				curl_setopt($login, CURLOPT_REFERER, 'https://cronos.cc.uoi.gr/unistudent/login.asp');
		
				$httppage = curl_exec($login);					// ανοίγουμε το λινκ αλλα με κωδικούς

				
				/* μετάβαση στον πίνακα βαθμολογιών */
				$url_grades = "https://cronos.cc.uoi.gr/unistudent/stud_CResults.asp?studPg=1&mnuid=mnu3&";

				curl_setopt($login, CURLOPT_URL, $url_grades);
				curl_setopt($login, CURLOPT_POST, false); 
				curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");  

				curl_setopt($login, CURLOPT_RETURNTRANSFER, true);              // μας επιστρέφει την σελίδα σε μεταβλητή
				curl_setopt($login, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:12.0) Gecko/20100101 Firefox/12.0");
				curl_setopt($login, CURLOPT_FOLLOWLOCATION, true);              // ακολοθούμε όλα τα redirects
				curl_setopt($login, CURLOPT_AUTOREFERER, true);
				curl_setopt($login, CURLOPT_REFERER,'https://cronos.cc.uoi.gr/unistudent/login.asp');
	
				$grades_data = curl_exec($login);
				curl_close($login);

				$grades_data = str_replace('<tr', "\n<tr", $grades_data);
				if(preg_match_all('/<td colspan="2" valign="top" class="topBorderLight">\([^0-9]+([0-9]+)\)(.*)<span class="redfonts" \/><\/td><td valign="top" class="topBorderLight">.*<\/td><td valign="top" class="topBorderLight">( [0-9]|[0-9]|[0-9].[0-9]| [0-9].[0-9]|[0-9],[0-9]| [0-9],[0-9])<\/td><td valign="top" class="topBorderLight">( [0-9]|[0-9]|[0-9].[0-9]| [0-9].[0-9]|[0-9],[0-9]| [0-9],[0-9])<\/td><td valign="top" class="topBorderLight">( [0-9]|[0-9]|[0-9].[0-9]| [0-9].[0-9]|[0-9],[0-9]| [0-9],[0-9])<\/td><td valign="top" class="topBorderLight"><span class="redFonts">( [0-9]|[0-9]|[0-9].[0-9]| [0-9].[0-9]|[0-9],[0-9]| [0-9],[0-9]|.|[0-9]{2})<\/span><\/td>/', $grades_data, $matches)) {
					}else {
						echo 'Huston we have a problem!';
				}
				$length = sizeof($matches[1]);
			?>
			<h3 align="center" >University Courses</h3>
			<hr><br>
			<div id="warning">
				<i>This project is still in Beta Version 1.0. Bugs send to vtzivaras@gmail.com</i>
			</div> </br> </br>
				<table>
					<tr>
						<td id="text_title" > <b>Courses</b> </td>
						<td id="num_title" > <b>Grades</b> </td>
						<td id="num_title" > <b>DM</b> </td>
						<td id="num_title" > <b>ECTS</b> </td>
					</tr>
				</table>
			
				<table>
					<?php for($i=0; $i<$length; $i++) { ?>
						<tr>
							<?php if($i%2 == 0){?>
								<td class="odd_course" > <?php echo $matches[2][$i]; ?></td>
								<td class="odd_grade" > <?php echo $matches[6][$i]; ?></td>
								<td class="odd_dm" > <?php echo $matches[3][$i]; ?></td>
								<td class="odd_ects" > <?php echo $matches[5][$i]; ?></td>
							<?php }else{ ?>
								<td class="course" > <?php echo $matches[2][$i]; ?></td>
								<td class="grade" > <?php echo $matches[6][$i]; ?></td>
								<td class="dm" > <?php echo $matches[3][$i]; ?></td>
								<td class="ects" > <?php echo $matches[5][$i]; ?></td>
							<?php }?>	
						</tr>
					<?php } ?>
				</table>  <br> <br>
				<?php
					function cal_grade($matches, $length)
					{
						$sum=0;
						$wfac=0;
							for($i=0; $i<$length; $i++) {
								if( ( ($matches[3][$i] == 1) || ($matches[3][$i] == 2) ) && ($matches[6][$i] >= 5) ) {
									
									$sum = $sum + $matches[6][$i]*1;
									
									$wfac = $wfac + 1;
								}else if( ( ($matches[3][$i] == 3) || ($matches[3][$i] == 4) ) &&  ($matches[6][$i] >= 5) ) {
									
									$sum = $sum + ($matches[6][$i]*1.5);
									
									$wfac = $wfac + 1.5;
								}else if( ($matches[3][$i] > 4)  && ($matches[6][$i] >= 5) ) {
									
									$sum = $sum + $matches[6][$i]*2;
									$wfac = $wfac + 2;
								}else {
								}
							}
							$total_grade = $sum/$wfac;
							return $total_grade;							
					}
				?>
				<div id="final_grade_title"> Your final Grade is : <?php echo cal_grade($matches, $length); ?> </div><br>
		</div>

		<div id="footer">
			Developed by <b>Tzivaras Vasilis</b>
		</div>
	</div>
</body>
</html>
