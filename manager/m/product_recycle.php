<?php 
$menu_flag = "product";
$pope	   = "pope_form";
include_once ("header.php");
$productarr  = get_set_arr('product');
if(empty($in['sid']))
{
	$sortinfo = null;
	$in['sid'] = 0;
}else{	 
	$sortinfo = $db->get_row("SELECT SiteID,ParentID,SiteNO,SiteName FROM ".DATATABLE."_order_site where CompanyID=".$_SESSION['uinfo']['ucompany']." and SiteID=".intval($in['sid'])." limit 0,1");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo SITE_NAME;?> - 管理平台</title>
<link href="css/main.css?v=<? echo VERID;?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/jquery.treeview.css" />
<link rel="stylesheet" href="css/showpage.css" />

<script src="../scripts/jquery.min.js" type="text/javascript"></script>
<script src="../scripts/jquery.cookie.js" type="text/javascript"></script>
<script src="../scripts/jquery.treeview.js" type="text/javascript"></script>
<script src="../scripts/jquery.blockUI.js" type="text/javascript"></script>

<script src="js/product.js?v=3<? echo VERID;?>" type="text/javascript"></script>
<script type="text/javascript">
/******tree****/
		$(function() {
			$("#tree").treeview({
				collapsed: true,
				animated: "medium",
				control:"#sidetreecontrol",
				persist: "location"
			});
		})
</script>


</head>

<body>
<?php include_once ("top.php");?>
    <div id="bodycontent">
    	<div class="lineblank"></div>
    	<div id="searchline">
        	<div class="leftdiv">
        	  <form id="FormSearch" name="FormSearch" method="get" action="product_recycle.php">
				<label><strong>&nbsp;&nbsp;名称/编号/拼音码/条码：</strong></label>
        	    <label>
        	      <input type="text" name="kw" id="kw" class="inputline" />
       	        </label>

       	        <label>
       	          <input name="searchbutton" type="submit" class="mainbtn" id="searchbutton" value=" 搜 索 " />
   	            </label>
   	          </form>
   	        </div>
            
			<div class="location"><strong>当前位置：</strong><a href="product.php">商品管理</a> &#8250;&#8250; <a href="product_recycle.php">下架商品</a> &#8250;&#8250; <a href="product.php"><? if(empty($sortinfo)) echo "所有商品"; else echo $sortinfo['SiteName'];?></a></div>
        </div>

    	
        <div class="line2"></div>
        <div class="bline">
       	  <div id="sortleft">
<!-- tree --> 

<div id="sidetree"> 
<div class="treeheader">&nbsp;<strong>商品分类</strong></div>  	  
<div id="sidetreecontrol"><span class="iconfont icon-suoyouleibie" style="color:#33a676;font-size:14px;"></span>&nbsp;&nbsp;[ <a href="?#">关闭</a> | <a href="?#">展开</a> ]</div>
<ul id="tree">
	<?php 
		$sortarr = $db->get_results("SELECT SiteID,ParentID,SiteName FROM ".DATATABLE."_order_site where CompanyID=".$_SESSION['uinfo']['ucompany']." ORDER BY SiteOrder DESC,SiteID ASC ");
		echo ShowTreeMenu($sortarr,0);	

	?>
	
</ul>
 </div>
<!-- tree -->   
       	  </div>
        	<div id="sortright">
            	<form id="MainForm" name="MainForm" method="post" action="">
        	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
               
               <thead>
                <tr>
                  <td width="4%" class="bottomlinebold"><label>
                 &nbsp;
                  </label></td>
                  <td width="6%" class="bottomlinebold">行号</td>
                  <td  class="bottomlinebold">名称</td>
                  <td width="12%" class="bottomlinebold">编号</td>
                    <?php
                    $price1_name = $productarr['product_price']['price1_name'] ? $productarr['product_price']['price1_name'] : "价格一";
                    $price2_name = $productarr['product_price']['price2_name'] ? $productarr['product_price']['price2_name'] : "价格二";
                    ?>
                  <td width="12%" class="bottomlinebold" align="right" title="<?php echo $price1_name; ?>"><?php echo $price1_name; ?></td>
                  <td width="15%" class="bottomlinebold" align="right" title="<?php echo $price2_name; ?>"><?php echo $price2_name; ?></td>
                  <td width="15%" class="bottomlinebold" align="center">管理</td>
                </tr>
     		 </thead> 
      		
      		<tbody>
<?
	$sqlmsg = '';
	if(!empty($in['sid'])) $sqlmsg .= " and SiteNO like '".$sortinfo['SiteNO']."%' ";
	if(!empty($in['kw']))  $sqlmsg .= " and (Name like '%".$in['kw']."%' OR CONCAT(Pinyi, Coding, Barcode) like '%".$in['kw']."%') ";
  //yangmm 2017-11-28 代理商只能看到自己商品的信息
  $userid=$_SESSION['uinfo']['userid'];
  $type = $db->get_row("SELECT UserType,UserFlag,UpperID FROM ".DATABASEU.DATATABLE."_order_user where UserID = ".$userid."");
  if($type['UserType']=='M' && $type['UserFlag']==2)    $sqlmsg .=" AND AgentID= ".$userid." ";
  if($type['UserType']=='S' && $type['UserFlag']==2)    $sqlmsg .=" AND AgentID= ".$type['UpperID']." ";

	$InfoDataNum = $db->get_row("SELECT count(*) AS allrow FROM ".DATATABLE."_view_index_site where CompanyID = ".$_SESSION['uinfo']['ucompany']." and FlagID=1 ".$sqlmsg."  ");
	$datasql   = "SELECT ID,SiteID,SiteName,OrderID,CommendID,Name,Coding,Price1,Price2,Units FROM ".DATATABLE."_view_index_site where CompanyID = ".$_SESSION['uinfo']['ucompany']." and FlagID=1  ".$sqlmsg." ORDER BY OrderID DESC, ID DESC";

	$page = new ShowPage;
    $page->PageSize = 20;
    $page->Total = $InfoDataNum['allrow'];
    $page->LinkAry = array("kw"=>$in['kw'],"sid"=>$in['sid']);        

	$list_data = $db->get_results($datasql." ".$page->OffSet());
	$n=1;
	if(!empty($list_data))
	{
		if(!empty($in['page'])) $n = ($in['page']-1)*$page->PageSize+1;
		foreach($list_data as $lsv)
		{
?>
                <tr id="line_<? echo $lsv['ID'];?>" class="bottomline" onmouseover="inStyle(this)"  onmouseout="outStyle(this)">
                  <td class="selectinput" ><input type="checkbox" name="selectedID[]" id="select_<? echo $lsv['ID'];?>" value="<? echo $lsv['ID'];?>" /></td>
                  <td ><? echo $n++;?></td>
                  <td ><a href="product_content.php?ID=<? echo $lsv['ID'];?>" target="_blank" title="<? echo $lsv['Name'];?>" ><? echo $lsv['Name'];?></a></td>
                  <td ><? echo $lsv['Coding'];?>&nbsp;</td>

                  <td class="TitleNUM"><? echo $lsv['Price1'];?> / <?=$lsv['Units']?></td>
                  
                  <td class="TitleNUM"><? echo $lsv['Price2']." 元 / ".$lsv['Units'];?></td>
                  <td align="right" ><a href="javascript:void(0);" onclick="do_restore('<? echo $lsv['ID'];?>');" >上架</a>&nbsp;｜&nbsp;<a href="javascript:void(0);" onclick="do_quite_delete('<? echo $lsv['ID'];?>','<? echo $lsv['SiteID'];?>');" >彻底删除</a></td>
                </tr>
<? } }else{?>
     			 <tr>
       				 <td colspan="8" height="30" align="center">此分类暂无商品，请选择下级分类或其他分类!</td>
       			 </tr>
<? }?>
 				</tbody>
                
              </table>
                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
     			 <tr>
       				 <td width="4%"  height="30" ><input id="chkall"  type="checkbox" name="chkall" value="on" onClick="CheckAll(this.form)" title="全选"></td>
   			       <td width="8%" >全选/取消</td>
   			       <td  class="sublink"><ul><li><a href="javascript:void(0);" onclick="going('restore','<? echo $in['sid'];?>')" >批量上架</a></li>
   			       <li><a href="javascript:void(0);" onclick="going('recycle_outexcel','<? echo $in['sid'];?>')" >批量导出</a></li>
   			       <li><a href="javascript:void(0);" onclick="going('quite_delete','<? echo $in['sid'];?>')" >彻底删除</a></li></ul></td>
       			     <td width="50%" align="right"><? echo $page->ShowLink('product_recycle.php');?></td>
     			 </tr>
              </table>
              <INPUT TYPE="hidden" name="referer" value ="" >

              </form>
       	  </div>
        </div>
        <br style="clear:both;" />
    </div>
    

<iframe name="exe_iframe" style="width:0; height:0;" frameborder="0" scrolling="no"></iframe>     
</body>
</html>
<?

 	function ShowTreeMenu($resultdata,$p_id) 
	{
		$frontMsg  = "";				
		foreach($resultdata as $key => $var)
		{
			if($var['ParentID'] == $p_id)
			{
				if($var['ParentID']=="0")
				{
					$frontMsg  .= '<li><a href="product_recycle.php?sid='.$var['SiteID'].'"><strong>'.$var['SiteName'].'</strong></a>';
				}	
				else
				{
					$frontMsg  .= '<li><a href="product_recycle.php?sid='.$var['SiteID'].'">'.$var['SiteName'].'</a>';
				}	

					$frontMsg2 = "";
					$frontMsg2 .= ShowTreeMenu($resultdata,$var['SiteID']);
					if(!empty($frontMsg2)) $frontMsg .= '<ul>'.$frontMsg2.'</ul>';
					$frontMsg .= '</li>';
			}
		}		
		return $frontMsg;
	}
?>
