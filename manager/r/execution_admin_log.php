<?php 
$menu_flag = "manager";
include_once ("header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo SITE_NAME;?> - 管理平台</title>
<link href="css/main.css?v=<? echo VERID;?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/showpage.css" />
<script src="js/manager.js?v=<? echo VERID;?>" type="text/javascript"></script>
</head>

<body>
<?php include_once ("top.php");?>
<?php include_once ("inc/son_menu_bar.php");?>
        
    <div id="bodycontent">
    	<div class="lineblank"></div>
    	<div id="searchline">
        	<div class="leftdiv">
        	  <form id="FormSearch" name="FormSearch" method="post" action="execution_admin_log.php<? if(!empty($in['cid'])) echo '?cid='.$in['cid'];?>">
        	    <label>
        	      &nbsp;&nbsp;用户/动作： <input type="text" name="kw" id="kw" class="inputline" />
       	        </label>

       	        <label>
       	          <input name="searchbutton" type="submit" class="mainbtn" id="searchbutton" value=" 搜 索 " />
   	            </label>
   	          </form>
   	        </div>
            
			<div class="location"><strong>当前位置：</strong><a href="execution_admin_log.php">操作日志</a></div>
        </div>
    	
        <div class="line2"></div>
        <div class="bline">
	<div id="sortleft">
         
<!-- tree --> 

<div class="leftlist"> 
<div class="treeheader">
<strong>客户</strong></div>  	  
<ul >
	<?php 
		$sortarr = $db->get_results("SELECT CompanyID,CompanyName,CompanySigned FROM ".DATABASEU.DATATABLE."_order_company where CompanyFlag='0' ORDER BY CompanyID DESC");

		$n = 1;
		foreach($sortarr as $areavar)
		{
			if($in['cid'] == $areavar['CompanyID']) $smsg = 'class="locationli"'; else $smsg ="";
			echo '<li> <a href="execution_admin_log.php?cid='.$areavar['CompanyID'].'" '.$smsg.'>'.$areavar['CompanyID'].'、'.$areavar['CompanySigned'].'</a></li>';
		}
	?>	
</ul>
</div>
<!-- tree -->   
       	  </div>


          <div id="sortright">
<?
	$sqlmsg = '';
	if(!empty($in['cid'])) $sqlmsg .= " and ExecutionCompany=".$in['cid']." ";

	if(!empty($in['kw']))  $sqlmsg .= " and (ExecutionUser like binary '%%".$in['kw']."%%' or ExecutionLink like binary '%%".$in['kw']."%%' ) ";
	$InfoDataNum = $db->get_row("SELECT count(*) AS allrow FROM ".DATATABLE."_order_execution where 1= 1 ".$sqlmsg." ");
	$page = new ShowPage;
    $page->PageSize = 50;
    $page->Total = $InfoDataNum['allrow'];
    $page->LinkAry = array("kw"=>$in['kw'],"cid"=>$in['cid']);        
	
	$datasql   = "SELECT ExecutionID,ExecutionUser,ExecutionLink,ExecutionAction,ExecutionData,ExecutionDate FROM ".DATATABLE."_order_execution where 1=1 ".$sqlmsg." ORDER BY ExecutionID DESC";
	$list_data = $db->get_results($datasql." ".$page->OffSet());

?>
          <form id="MainForm" name="MainForm" method="post" action="" target="exe_iframe" >
        	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <thead>
                <tr>
                  <td width="8%" class="bottomlinebold">编号</td>
                  <td width="14%" class="bottomlinebold">帐号</td>				  
				  <td width="14%" class="bottomlinebold">动作</td>
                  <td width="16%" class="bottomlinebold">操作时间</td>
				  <td  class="bottomlinebold">操作链接</td>
                </tr>
     		 </thead>      		
      		<tbody>
<?
if(!empty($list_data))
{
     foreach($list_data as $lsv)
	 {
?>
               <tr id="line_<? echo $lsv['ExecutionID'];?>" title="<? print_r(unserialize($lsv['ExecutionData']));?>" class="bottomline" onmouseover="inStyle(this)"  onmouseout="outStyle(this)">
                  <td ><? echo $lsv['ExecutionID'];?></td>
                  <td ><? echo $lsv['ExecutionUser'];?></td>
				  <td ><? echo $lsv['ExecutionAction'];?></td>
				  <td class="TitleNUM2"><? echo date("y-m-d H:i",$lsv['ExecutionDate']);?></td>
                  <td class="TitleNUM2"><? echo $lsv['ExecutionLink'];?></td>
                </tr>
<? } }else{?>
     			 <tr>
       				 <td colspan="8" height="30" align="center">暂无符合此条件的内容!</td>
       			 </tr>
<? }?>
 				</tbody>                
              </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
     			 <tr>
       			     <td  align="right"><? echo $page->ShowLink('execution_admin_log.php');?></td>
     			 </tr>
              </table>
              <INPUT TYPE="hidden" name="referer" value ="" >
              </form>
       	  </div>
		</div>
        <br style="clear:both;" />
    </div>
    
<? include_once ("bottom.php");?>
<iframe name="exe_iframe" style="width:0; height:0;" frameborder="0" scrolling="no"></iframe>  
</body>
</html>