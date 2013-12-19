<?php include('includes/header.php');?>
<?php include('includes/login/auth.php');?>
<?php include('includes/subscribers/main.php');?>
<?php include('includes/helpers/short.php');?>
<?php
	//vars
	if(isset($_GET['s'])) $s = $_GET['s'];
	else $s = '';

/*
	if(get_app_info('is_sub_user')) 
	{
		if(get_app_info('app')!=get_app_info('restricted_to_app'))
		{
			echo '<script type="text/javascript">window.location="'.get_app_info('path').'/list?i='.get_app_info('restricted_to_app').'"</script>';
			exit;
		}
		$q = 'SELECT app FROM lists WHERE id = '.mysqli_real_escape_string($mysqli, $_GET['l']);
		$r = mysqli_query($mysqli, $q);
		if ($r)
		{
		    while($row = mysqli_fetch_array($r))
		    {
				$a = $row['app'];
		    }  
		    if($a!=get_app_info('restricted_to_app'))
		    {
			    echo '<script type="text/javascript">window.location="'.get_app_info('path').'/list?i='.get_app_info('restricted_to_app').'"</script>';
				exit;
		    }
		}
	}
	
	//vars
	if(isset($_GET['s'])) $s = $_GET['s'];
	else $s = '';
	if(isset($_GET['c'])) $c = $_GET['c'];
	else $c = '';
	if(isset($_GET['p'])) $p = $_GET['p'];
	else $p = '';
	if(isset($_GET['a'])) $a = $_GET['a'];
	else $a = '';
	if(isset($_GET['u'])) $u = $_GET['u'];
	else $u = '';
	if(isset($_GET['b'])) $b = $_GET['b'];
	else $b = '';
	if(isset($_GET['cp'])) $cp = $_GET['cp'];
	else $cp = '';
*/
?>
<div class="row-fluid">
    <div class="span2">
        <?php include('includes/sidebar.php');?>
    </div> 
    <div class="span10">
    	<h2><?php echo _('Search and Edit Users');?></h2> <br/>
<!-- 
    	<button class="btn" onclick="window.location='<?php echo get_app_info('path');?>/update-list?i=<?php echo get_app_info('app');?>&l=<?php echo $_GET['l'];?>'"><i class="icon-plus-sign"></i> <?php echo _('Add subscribers');?></button> 
    	<button class="btn" onclick="window.location='<?php echo get_app_info('path');?>/delete-from-list?i=<?php echo get_app_info('app');?>&l=<?php echo $_GET['l'];?>'"><i class="icon-minus-sign"></i> <?php echo _('Delete subscribers');?></button> 
    	<button class="btn" onclick="window.location='<?php echo get_app_info('path');?>/unsubscribe-from-list?i=<?php echo get_app_info('app');?>&l=<?php echo $_GET['l'];?>'"><i class="icon-ban-circle"></i> <?php echo _('Mass unsubscribe');?></button>
-->
    	<?php 
    		//export according to which section user is on
/*
    		if($a=='' && $c=='' && $u=='' && $b=='' && $cp=='')
    		{
	    		$filter = '';
	    		$filter_val = '';
	    		$export_title = _('all subscribers');
    		}
    		else if($a!='')
    		{
	    		$filter = 'a';
	    		$filter_val = $a;
	    		$export_title = _('active subscribers');
    		}
    		else if($c!='')
    		{
	    		$filter = 'c';
	    		$filter_val = $c;
	    		$export_title = _('unconfirmed subscribers');
    		}  
    		else if($u!='')
    		{
	    		$filter = 'u';
	    		$filter_val = $u;
	    		$export_title = _('unsubscribers');
    		} 
    		else if($b!='')
    		{
	    		$filter = 'b';
	    		$filter_val = $b;
	    		$export_title = _('bounced subscribers');
    		}
    		else if($cp!='')
    		{
	    		$filter = 'cp';
	    		$filter_val = $cp;
	    		$export_title = _('subscribers who marked your email as spam');
    		}
*/
    	?>
		<form class="form-search" action="<?php echo get_app_info('path');?>/search-and-edit-users" method="GET" style="float:left;">
    		<input type="hidden" name="i" value="<?php echo get_app_info('app');?>">
			<input type="text" class="input-medium search-query" name="s" value="<?php echo $s;?>">
			<button type="submit" class="btn"><i class="icon-search"></i> <?php echo _('Search');?></button>
		</form>
    	
    	<br/><br/>
		
	    <table class="table table-striped table-condensed responsive">
		  <tbody>
  			<tr>
			<?php 
				if($s!="")
				{
					$additional_clause = "";
					if (get_app_data('app_name')!="")
					{
						$app_id = get_app_info('app');
						$additional_clause = "AND a.id=".$app_id;
					}
					
					$q = 'SELECT s.id sid, s.userID,s.name ,s.email,s.custom_fields,s.list,s.unsubscribed,s.bounced,s.bounce_soft,s.complaint, s.timestamp,
					s.confirmed, l.id lid, l.name lname, a.id aid, a.app_name aname
					FROM subscribers s
					JOIN lists l ON s.list = l.id
					JOIN apps a ON l.app = a.id

					WHERE (s.email like "%'.mysqli_real_escape_string($mysqli, $s).'%"
					OR s.name like "%'.mysqli_real_escape_string($mysqli, $s).'%")
					ORDER by a.id,l.id,s.id asc LIMIT 200';

					// echo $q;
					$r = mysqli_query($mysqli, $q);
					$all_rows = mysqli_fetch_all($r, MYSQLI_ASSOC);
					$array_thisbrand = array();
					$array_otherbrands = array();
					foreach ($all_rows as $row) 
					{
						if($row['aid'] == get_app_info('app'))
							array_push($array_thisbrand, $row);
						else
							array_push($array_otherbrands, $row);
					}
					// print_r($array_thisbrand);
					// echo "----------------";
					// print_r($array_otherbrands);

					function printrowheader()
					{
					?>
					  
					    <tr>
					      <th><?php echo _('');?></th>
					      <th><?php echo _('Name');?></th>
					      <th><?php echo _('Email');?></th>
					      <th><?php echo _('Brand');?></th>
					      <th><?php echo _('List');?></th>
					      <th><?php echo _('Last activity');?></th>
					      <th><?php echo _('Status');?></th>
					      <th><?php echo _('Delete');?></th>
					    </tr>
					  
					  <?
					}
					
					function printrow($row)
					{
			  			$id = $row['sid'];
			  			$name = stripslashes($row['name']);
			  			$email = stripslashes($row['email']);
			  			$brand = stripslashes($row['aname']);
			  			$list = stripslashes($row['lname']);
				    	$unsubscribed = $row['unsubscribed'];
			  			$bounced = $row['bounced'];
			  			$complaint = $row['complaint'];
			  			$confirmed = $row['confirmed'];
			  			$timestamp = parse_date($row['timestamp'], 'short', true);

			  			if($unsubscribed==0)
			  				$unsubscribed = '<a href="javascript:void(0)" id="subscription-'.$id.'" class="subscription label label-success" data-action="unsubscribe" title="Unsubscribe '.$email.'">'._('Subscribed').'</a>';
			  			else if($unsubscribed==1)
			  				$unsubscribed = '<a href="javascript:void(0)" id="subscription-'.$id.'" class="subscription label label-important" data-action="resubscribe" title="Resubscribe '.$email.'">'._('Unsubscribed').'</a>';
						if($bounced==1)
			  				$unsubscribed = '<a href="javascript:void(0)" id="subscription-'.$id.'" class="label label-warning" data-action="" title="Nothing can be done about it">'._('Bounced').'</a>';
			  			if($complaint==1)
			  				$unsubscribed = '<a href="javascript:void(0)" id="subscription-'.$id.'" class="label label-warning" data-action="" title="Nothing can be done about it">'._('Marked as spam').'</a>';
			  			if($confirmed==0)
			  				$unsubscribed = '<a href="javascript:void(0)" id="subscription-'.$id.'" class="subscription label" data-action="confirm" title="Confirm '.$email.'">'._('Unconfirmed').'</a>';

			  			$delete = '<a href="javascript:void(0)" title="Delete '.$email.'?" id="delete-'.$id.'" data-email="'.$email.'" class="delete-subscriber"><i class="icon icon-trash"></i></a>'
				    	?>
				    	<tr id="row-<?echo $id;?>">
						<td><input type="checkbox" id="" name="rows_to_edit[<?echo $id;?>]" class="multi_checkbox checkall" value=""></td>
						<td class="edit" id="name-<?echo $id;?>"><?php echo $name;?></td>
						<td class="edit" id="email-<?echo $id;?>"><?php echo $email;?></td>
						<td><?php echo $brand;?></td>
						<td><?php echo $list;?></td>
						<td><?php echo $timestamp;?></td>
						<td><?php echo $unsubscribed;?></td>
						<td><?php echo $delete;?></td>
						</tr>
						<?php

					}
					if(count($array_thisbrand)>0)
					{
						?><tr><td colspan="8"><br><h2><?echo count($array_thisbrand)?> Results found in <?echo get_app_data('app_name')?><h2></td></tr><?
						printrowheader();
						foreach ($array_thisbrand as $row) {
							printrow($row);
						}
						
					}
					if(count($array_otherbrands)>0)
					{
						?><tr><td colspan="8"><br><h2><?echo count($array_otherbrands)?> Results also found in Other Brands<h2></td></tr><?
						//printrowheader();
						foreach ($array_otherbrands as $row) {
							printrow($row);
						}
					}
					if(count($array_thisbrand)==0 && count($array_otherbrands)==0)
					{
						//printrowheader();
						?>
						<tr>
		  				<td colspan="8">No subscribers found.</td>
		  				</tr>
						<?php

					}


				}
				else
				{
					?>
					<tr>
	  				<td>No subscribers found.</td>
	  				</tr>
					<?php
				}

			?>
  			</tr>
		    
		  </tbody>
		</table>
		
		<?php pagination($limit);?>
    </div>   
</div>
<script>
$(document).ready(function() {
	$('.edit').editable('search-and-edit-save.php',{
         indicator : 'Saving...',
         tooltip   : 'Click to edit...',
         onblur	   : 'submit'
     });

    $(".subscription").click(function(event) {
    	elem = $(event.target);
        action = elem.data("action");
        elem_id = elem.prop('id');
        subscriber_id = elem_id.split('-')[1];
        $.post("includes/subscribers/unsubscribe.php", { subscriber_id: subscriber_id, action: action},
		function(data) {
		  if(data)
		  {
		  	if(elem.text().trim()=='Subscribed')
		  	{
		  		elem.data("action", "resubscribe");
		  		elem.text("Unsubscribed").removeClass("label-success").addClass("label-important");
		  		title = elem.attr('data-original-title');
		  		elem.attr("data-original-title", title.replace("Unsubscribe", "Resubscribe"));
		    }
		    else
		    {
		  		elem.data("action", "unsubscribe");
		  		elem.text("Subscribed").removeClass("label-important").addClass("label-success");
		  		title = elem.attr('data-original-title');
		  		elem.attr("data-original-title", title.replace("Resubscribe", "Unsubscribe").replace("Confirm", "Unsubscribe"));
		    }
		  }
		  else
		  {
		  	alert("Sorry, unable to unsubscribe. Please try again later!");
		  }
		});
    });
	$(".delete-subscriber").click(function(e){
		e.preventDefault();
    	elem = $(e.target);
    	email = elem.parent().data("email");
        elem_id = elem.parent().prop('id');
        subscriber_id = elem_id.split('-')[1];
        console.log(elem);
        console.log(email);
        console.log(subscriber_id);

		c = confirm("Confirm delete "+email+"?");
		if(c)
		{
			$.post("includes/subscribers/delete.php", { subscriber_id: subscriber_id },
			  function(data) {
			  		console.log(data);
			      if(data)
			      {
			      	$("#row-"+subscriber_id).fadeOut();
			      }
			      else
			      {
			      	alert("Sorry, unable to delete. Please try again later!");
			      }
			  }
			);
		}
	});

});
</script>


<?php include('includes/footer.php');?>
