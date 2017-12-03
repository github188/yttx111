<?php 
$menu_flag = "product";
$pope	   = "pope_view";
include_once ("header.php");

if(empty($in['sid']))
{
	$sortinfo = null;
	$in['sid'] = 0;
}else{	 
	$sortinfo = $db->get_row("SELECT * FROM ".DATATABLE."_order_site where CompanyID=".$_SESSION['uinfo']['ucompany']." and SiteID=".intval($in['sid'])." limit 0,1");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo SITE_NAME;?> - 管理平台</title>
<link href="css/main.css?v=<? echo VERID;?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/jquery.treeview.css" />

<script src="../scripts/jquery.min.js" type="text/javascript"></script>
<script src="../scripts/jquery.cookie.js" type="text/javascript"></script>
<script src="../scripts/jquery.treeview.js" type="text/javascript"></script>
<script src="../scripts/jquery.blockUI.js" type="text/javascript"></script>

<script src="js/product.js?v=<? echo VERID;?>" type="text/javascript"></script>

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
        	<div class="rightdiv">
        	 <div class="locationl"><a name="editname" id="editname"></a><strong>当前位置：</strong><a href="product.php">商品管理</a> &#8250;&#8250; <a href="product_add.php">商品分类</a> </div>
          </div>            
        </div>
    	
        <div class="line2"></div>
        <div class="bline" >
       	  <div id="sortleft">
<!-- tree --> 
<div id="sidetree"> 
<div class="treeheader">&nbsp;<strong>商品分类</strong>&nbsp;&nbsp;(<a href="product_sort.php" title="新增分类后，刷新后方能看到效果。">刷新分类</a>)</div>  	  
<div id="sidetreecontrol"><span class="iconfont icon-suoyouleibie" style="color:#33a676;font-size:14px;"></span>&nbsp;&nbsp;[ <a href="?#">关闭</a> | <a href="?#">展开</a> ]</div>
<ul id="tree">
	<?php 
		$sortarr = $db->get_results("SELECT SiteID,ParentID,SiteNO,SiteName,SitePinyi,SiteOrder,Content FROM ".DATATABLE."_order_site where CompanyID=".$_SESSION['uinfo']['ucompany']." ORDER BY SiteOrder DESC,SiteID ASC ");
		echo ShowTreeMenuList($sortarr,0);
	?>	
</ul>
 </div>
<!-- tree -->   
       	  </div>


<div id="sortright">
 <form id="MainForm" name="MainForm" enctype="multipart/form-data" method="post" target=""  action="">
			<fieldset title="“*”为必填项！" class="fieldsetstyle">
			<legend>新增分类</legend>
       
              <table width="92%" border="0" cellpadding="4" cellspacing="1" bgcolor="#FFFFFF" class="inputstyle">
                 <tr>
                  <td width="16%" bgcolor="#F0F0F0"><div align="right">上级分类：</div></td>
                  <td width="55%" bgcolor="#FFFFFF"><label>
                  <select name="data_ParentID" id="data_ParentID" class="select2" style="width:544px;">
                    <option value="0">⊙ 顶级分类</option>
                    <? 
					echo ShowTreeMenu($sortarr,0,$in['sid'],1);
					?>
                  </select>
                  <span class="red">*</span></label></td>
                </tr>               
                <tr>
                  <td width="16%" bgcolor="#F0F0F0"><div align="right">分类名称：</div></td>
                  <td width="55%" bgcolor="#FFFFFF"><label>
                    <input type="text" name="data_SiteName" id="data_SiteName" style="width:77%;" />
                    <span class="red">*</span></label></td>
                </tr>
                <tr>
                  <td bgcolor="#F0F0F0"><div align="right">排序权重：</div></td>
                  <td bgcolor="#FFFFFF"><input name="data_SiteOrder" type="text" id="data_SiteOrder" value="0" onKeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" maxlength="8" style="width:77%;" /></td>
                </tr>
                <tr>
                  <td bgcolor="#F0F0F0"><div align="right">分类描述：</div></td>
                  <td bgcolor="#FFFFFF"><textarea name="data_Content" cols="70" rows="4" id="data_Content" style="width:77%;"></textarea></td>
                </tr>
            </table>

</fieldset>
            <div class="rightdiv sublink" style="padding-right:20px;">
			<input name="saveproductsort" type="button" class="button_1" id="saveproductsort" value="保 存" onclick="do_save_sort();" />
			</div>
            
			<fieldset title="“*”为必填项！" class="fieldsetstyle">
			<legend>修改分类（先点击左边相应的分类）</legend>
			<table width="92%" border="0" cellpadding="4" cellspacing="1" bgcolor="#FFFFFF" class="inputstyle">
                 <tr>
                  <td width="16%" bgcolor="#F0F0F0"><div align="right">上级分类：</div></td>
                  <td width="55%" bgcolor="#FFFFFF"><label>
                   <input type="hidden" name="edit_SiteID" id="edit_SiteID" value=""/>
                  <select name="edit_ParentID" id="edit_ParentID" class="select2" style="width:544px;">
                    <option value="0">⊙ 顶级分类</option>
                    <? 
					echo ShowTreeMenu($sortarr,0,$in['sid'],1);
					?>
                  </select>
                  <span class="red">*</span></label></td>
                </tr>               
                <tr>
                  <td width="16%" bgcolor="#F0F0F0"><div align="right">分类名称：</div></td>
                  <td width="55%" bgcolor="#FFFFFF"><label>
                    <input type="text" name="edit_SiteName" id="edit_SiteName" style="width:77%;" />
                    <span class="red">*</span></label></td>
                </tr>
                <tr>
                  <td bgcolor="#F0F0F0"><div align="right">排序权重：</div></td>
                  <td bgcolor="#FFFFFF"><input name="edit_SiteOrder" type="text" id="edit_SiteOrder" value="0" onKeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" maxlength="8" style="width:77%;" /></td>
                </tr>
                <tr>
                  <td bgcolor="#F0F0F0"><div align="right">分类描述：</div></td>
                  <td bgcolor="#FFFFFF"><textarea name="edit_Content" cols="70" rows="4" id="edit_Content" style="width:77%;"></textarea></td>
                </tr>
            </table>
           </fieldset>    
            
            <div class="rightdiv sublink" style="padding-right:20px;">
			<input name="saveproductsort" type="button" class="button_1" id="editproductsort" value="保 存" onclick="do_save_edit_sort();" />
			<input name="saveproductsort" type="button" class="button_3" id="delproductsort" value="删 除" onclick="do_delete_sort();" />
			</div>
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
 	function ShowTreeMenu($resultdata,$p_id=0,$s_id=0,$layer=1) 
	{
		$frontMsg  = "";
		$frontTitleMsg = "";
		$selectmsg = "";
		
		if($var['ParentID']=="0") $layer = 1; else $layer++;
					
		foreach($resultdata as $key => $var)
		{
			if($var['ParentID'] == $p_id)
			{
				$repeatMsg = str_repeat("-+-", $layer-1);
				if($var['SiteID'] == $s_id) $selectmsg = "selected"; else $selectmsg = "";
				
				$frontMsg  .= "<option value='".$var['SiteID']."' ".$selectmsg." >┠-".$frontTitleMsg.$repeatMsg.$var['SiteName']."</option>";	

				$frontMsg2  = "";
				$frontMsg2 .= ShowTreeMenu($resultdata,$var['SiteID'],$s_id,$layer);
				$frontMsg  .= $frontMsg2;
			}
		}		
		return $frontMsg;
	}
	
	function charblank($char)
	{
		if(strlen($char) > 5)
		{
			$rchar = substr($char,0,4);
		}else{
			$rchar = $char.str_repeat(" -", (4-strlen($char)));
		}
		return $rchar;
	}

 	function ShowTreeMenuList($resultdata,$p_id) 
	{
		$frontMsg  = "";				
		foreach($resultdata as $key => $var)
		{
			$var['Content'] = str_replace("\r","",$var['Content']);
			$var['Content'] = str_replace("\n","",$var['Content']);
			if($var['ParentID'] == $p_id)
			{
				if($var['ParentID']=="0")
				{
					$frontMsg  .= '<li><a href="#editname" onclick="set_edit_sort(\''.$var['SiteID'].'\',\''.$var['ParentID'].'\',\''.$var['SiteName'].'\',\''.$var['SiteOrder'].'\',\''.$var['Content'].'\');" ><strong>'.$var['SiteName'].'</strong></a>';
				}	
				else
				{
					$frontMsg  .= '<li><a href="#editname" onclick="set_edit_sort(\''.$var['SiteID'].'\',\''.$var['ParentID'].'\',\''.$var['SiteName'].'\',\''.$var['SiteOrder'].'\',\''.$var['Content'].'\');" >'.$var['SiteName'].'</a>';
				}
				$frontMsg2 = "";
				$frontMsg2 .= ShowTreeMenuList($resultdata,$var['SiteID']);
				if(!empty($frontMsg2)) $frontMsg .= '<ul>'.$frontMsg2.'</ul>';
				$frontMsg .= '</li>';
			}
		}		
		return $frontMsg;
	}
?>