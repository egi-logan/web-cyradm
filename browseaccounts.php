<!-- #################################### Start browseaccounts ################################# -->
          <tr>
        <td width="10">&nbsp; </td>
        <td valign="top">

<?php
       $cyr_conn = new cyradm;

       $cyr_conn -> imap_login();

        print "<h3>Browse accounts for domain <font color=red>$domain</font></h3>";
	if (!isset($row_pos)){
		$row_pos=0;
	}
        $query="SELECT * FROM accountuser where domain_name='$domain' ORDER BY username";
	$handle=DB::connect($DSN, true);
	$result=$handle->limitQuery($query,$row_pos,10);
	$cnt=$result->numRows($result);

        $query2="SELECT * FROM accountuser where domain_name='$domain' ORDER BY username";
//        $result2=mysql_db_query($MYSQL_DB,$query2);
	$result2=$handle->query($query2);

	
	$total=$result2->numRows($result2);
	
        $b=0;
	if ($cnt!=0){
		print "Total accounts: ".$total."<p>";
		print "<table cellspacing=\"2\" cellpadding=\"0\"><tr>";
		print "<td class=\"navi\">";
	        print "<a href=\"index.php?action=newaccount&domain=$domain&username=$username\">Add&nbsp;new&nbsp;account</a>";
		print "</td>";
	

		$prev = $row_pos -10;
		$next = $row_pos +10;

		if ($row_pos<10){
			print "<td class=\"navi\"><a href=\"#\">Previous&nbsp;10&nbsp;entries</a></td>";
		}
		else {
			print "<td class=\"navi\"><a href=\"index.php?action=accounts&domain=$domain&row_pos=$prev\">
			Previous&nbsp;10&nbsp;entries</a></td>"; 	
		}

		if ($next>$total){
			print "<td class=\"navi\"><a href=\"#\">Next&nbsp;10&nbsp;entries</a></td>";
		}
		else {
			print "<td class=\"navi\"><a href=\"index.php?action=accounts&domain=$domain&row_pos=$next\">
			Next&nbsp;10&nbsp;entries</a></td>";
		}
		print "</tr></table><p>";


	        print "<table border=\"0\">\n";
		print "<tbody>";
	        print "<tr>";
	        print "<th colspan=\"4\">actions</th>";
	        print "<th>Email address</th>";
	        print "<th>Username</th>";
	        print "<th>Password</th>";
	        print "<th>Quota used</th>";
	        print "</tr>";


	        for ($c=0;$c<$cnt;$c++){

	        if ($b==0){
			$cssrow="row1";
	        	$b=1;
	        }
	        else{
			$cssrow="row2";
	        	$b=0;
	        }

		$row=$result->fetchRow(DB_FETCHMODE_ASSOC,$c);
		$domain=$row['domain_name'];
		$username=$row['username'];

	        print "\n<tr class=\"$cssrow\">";
	        print "\n<td><a href=\"index.php?action=editaccount&domain=$domain&username=$username\">Edit account</a></td>";
	        print "\n<td><a href=\"index.php?action=deleteaccount&domain=$domain&username=$username\">Delete account</a></td>";
	        print "\n<td><a href=\"index.php?action=setquota&domain=$domain&username=$username\">Set Quota</a></td>";
	        print "\n<td><a href=\"index.php?action=catch&domain=$domain&username=$username\">Set Catch all</a></td>";
	        print "\n<td>";
		$query2="SELECT * FROM virtual WHERE username='$username'"; 
		$result2=$handle->query($query2);

		$cnt2=$result2->numRows($result2);
		
		for ($c2=0;$c2<$cnt2;$c2++){
			# Print All Emailadresses found for the account
			$row=$result2->fetchRow(DB_FETCHMODE_ASSOC, $c2);
			print $row['alias']."<br>";
			}
	
	        print "</td>\n<td>";
	        print $username;
	        print "</td>\n<td>";
	//        print mysql_result($hnd,$c,'password');
		print "******";
	        print "</td>\n<td>";

		if ($DOMAIN_AS_PREFIX) {
			$quota= $cyr_conn->getquota("user/$username");
		}
		else {
			$quota= $cyr_conn->getquota("user.$username");
		}
			if ($quota[used]!="NOT-SET"){
			$q_used=$quota[used];
			$q_total=$quota[qmax];	
			$q_percent=100*$q_used/$q_total;
			print $quota[used]." Kbytes out of ";
			print $quota[qmax]." Kbytes (".sprintf("%.2f",$q_percent)." %)";
		}
		else{
			print "Quota not set";
		}


	        print "&nbsp;</td>\n</tr>\n";


	        }
		print "\n</tbody>\n";
		print "</table>\n";

		}
                else{
                        print "\nNo accounts found\n<p>";

		print "<table><tr>";
		print "<td class=\"navi\">\n";
		print "<a href=\"index.php?action=newaccount&domain=$domain&username=$username\">Add&nbsp;new&nbsp;account</a>";
                print "\n</td></tr></table>\n";

	}

?>

<!-- ##################################### End browseaccounts.php #################################### -->


