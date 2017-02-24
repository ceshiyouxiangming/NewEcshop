<!-- $Id: wholesale_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'validator.js,../js/transport.js')); ?>
<div class="main-div">
<form method="post" action="wholesale.php" name="theForm" enctype="multipart/form-data" onSubmit="return validate()">
<table width="100%">
  <tr>
    <td width="14%" align="right"><?php echo $this->_var['lang']['pls_search_goods']; ?></td>
    <td width="86%"><!-- 分类 -->
      <select name="cat_id"><option value="0"><?php echo $this->_var['lang']['custom_goods_cat']; ?></option><?php echo $this->_var['cat_list']; ?></select>
      <!-- 品牌 -->
      <select name="brand_id"><option value="0"><?php echo $this->_var['lang']['custom_goods_brand']; ?></option><?php echo $this->html_options(array('options'=>$this->_var['brand_list'])); ?></select>
      <!-- 关键字 -->
      <?php echo $this->_var['lang']['label_search_goods']; ?><input name="keyword" type="text" id="keyword" size="10">
      <!-- 搜索 -->
      <input name="search" type="button" id="search" value="<?php echo $this->_var['lang']['button_search']; ?>" class="button" onclick="searchGoods()" /></td>
  </tr>
  <tr>
    <td class="label"><?php echo $this->_var['lang']['label_goods_name']; ?></td>
    <td><table width="100%" border="0">
  <tr>
    <td width="46%"><select name="src_goods_lists" id="src_goods_lists" size="14" style="width:100%" multiple="true">
              </select></td>
    <td rowspan="2" width="8%" style="text-align:center;">
      <p><input type="button" value=">>" id="addAllGoods" class="button" /></p>
      <p><input type="button" value=">" id="addGoods" class="button" /></p>
      <p><input type="button" value="<" id="delGoods" class="button" /></p>
      <p><input type="button" value="<<" id="delAllGoods" class="button" /></p>
    </td>
    <td width="46%"><select name="dst_goods_lists" id="dst_goods_lists" size="14" style="width:100%" multiple="true"></select></td>
  </tr>
  </table>
    </td>
  </tr>
  <tr>
    <td class="label"><?php echo $this->_var['lang']['label_rank_name']; ?></td>
    <td><?php $_from = $this->_var['user_rank_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'rank');if (count($_from)):
    foreach ($_from AS $this->_var['rank']):
?> 
      <input name="rank_id[]" type="checkbox" id="rank_id[]" value="<?php echo $this->_var['rank']['rank_id']; ?>" <?php if ($this->_var['rank']['checked']): ?>checked="checked"<?php endif; ?> />
      <?php echo $this->_var['rank']['rank_name']; ?> <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?></td>
  </tr>
  <tr>
    <td class="label"><?php echo $this->_var['lang']['label_enabled']; ?></td>
    <td><label>
        <input type="radio" name="enabled" value="1" <?php if ($this->_var['wholesale']['enabled']): ?>checked="checked"<?php endif; ?> />
        <?php echo $this->_var['lang']['yes']; ?></label>
      <label>
        <input type="radio" name="enabled" value="0" <?php if (! $this->_var['wholesale']['enabled']): ?>checked="checked"<?php endif; ?> />
        <?php echo $this->_var['lang']['no']; ?></label>    </td>
  </tr>
</table>

<table width="100%">
  <tr>
    <td colspan="2" align="center">
      <input type="submit" class="button" value="<?php echo $this->_var['lang']['button_submit']; ?>" />
      <input type="reset" class="button" value="<?php echo $this->_var['lang']['button_reset']; ?>" />
      <input type="hidden" name="act" value="<?php echo $this->_var['form_action']; ?>" />
      <input name="goods_ids" type="hidden" value="" />
    </td>
  </tr>
</table>
</form>
</div>

<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js')); ?>

<script language="JavaScript">
<!--
onload = function()
{
  // 开始检查订单
  startCheckOrder();
}

/**
 * 检查表单输入的数据
 */
function validate()
{	
	var dst_obj = document.forms['theForm'];
	copy_search_result(dst_obj);
	
  return true;
}

function copy_search_result(dst_obj)
{
		var goods_lists = Utils.$('dst_goods_lists');
		for (var i=0, l=goods_lists.options.length; i<l; i++)
		{
				var separator = (i==0) ? '' : ',';
				dst_obj.goods_ids.value += separator + goods_lists.options[i].value;
		}
}

function searchGoods()
{
  var filter = new Object;
  filter.cat_id = document.forms['theForm'].elements['cat_id'].value;
	filter.brand_id = document.forms['theForm'].elements['brand_id'].value;
	filter.keyword = document.forms['theForm'].elements['keyword'].value;

  Ajax.call('wholesale.php?is_ajax=1&act=search_goods', filter, searchGoodsResponse, 'GET', 'JSON');
}

function searchGoodsResponse(result)
{
  if (result.error == '1' && result.message != '')
  {
    alert(result.message);
		return;
  }

  var sel = document.forms['theForm'].elements['src_goods_lists'];

  sel.length = 0;

  /* 创建 options */
  var goods = result.content;
  if (goods)
  {
		for (i = 0; i < goods.length; i++)
		{
			var opt = document.createElement("OPTION");
			opt.value = goods[i].goods_id;
			opt.text  = goods[i].goods_name;
			sel.options.add(opt);
		}
  }

  return;
}

/* 操作自定义商品的Select Box */
var MySelectBox;
var MySelectBox2;
if (!MySelectBox)
{
		var global = $import("../js/global.js","js");
		global.onload = global.onreadystatechange= function()
		{
				if(this.readyState && this.readyState=="loading")return;
				var selectbox = $import("js/selectbox.js","js");
				selectbox.onload = selectbox.onreadystatechange = function()
				{
						if(this.readyState && this.readyState=="loading")return;
						MySelectBox = new SelectBox('src_goods_fields', 'dst_goods_fields');
						MySelectBox2 = new SelectBox('src_goods_lists', 'dst_goods_lists', true);
				}
		}
}
if (Utils.$('addItem'))
{
		Utils.$('addItem').onclick = function ()
		{
				MySelectBox.addItem();
		}
}
if (Utils.$('delItem'))
{
		Utils.$('delItem').onclick = function ()
		{
				MySelectBox.delItem();
		}
}
if (Utils.$('addAllItem'))
{
		Utils.$('addAllItem').onclick = function ()
		{
				MySelectBox.addItem(true);
		}
}
if (Utils.$('delAllItem'))
{
		Utils.$('delAllItem').onclick = function ()
		{
				MySelectBox.delItem(true);
		}
}
if (Utils.$('src_goods_fields'))
{
		Utils.$('src_goods_fields').ondblclick = function ()
		{
				MySelectBox.addItem();
		}
}
if (Utils.$('dst_goods_fields'))
{
		Utils.$('dst_goods_fields').ondblclick = function ()
		{
				MySelectBox.delItem();
		}
}
if (Utils.$('mvUp'))
{
		Utils.$('mvUp').onclick = function ()
		{
				MySelectBox.moveItem('up');
		}
}
if (Utils.$('mvDown'))
{
		Utils.$('mvDown').onclick = function ()
		{
				MySelectBox.moveItem('down');
		}
}

if (Utils.$('addGoods'))
{
		Utils.$('addGoods').onclick = function ()
		{
				MySelectBox2.addItem();
		}
}
if (Utils.$('delGoods'))
{
		Utils.$('delGoods').onclick = function ()
		{
				MySelectBox2.delItem();
		}
}
if (Utils.$('addAllGoods'))
{
		Utils.$('addAllGoods').onclick = function ()
		{
				MySelectBox2.addItem(true);
		}
}
if (Utils.$('delAllGoods'))
{
		Utils.$('delAllGoods').onclick = function ()
		{
				MySelectBox2.delItem(true);
		}
}
if (Utils.$('src_goods_lists'))
{
		Utils.$('src_goods_lists').ondblclick = function ()
		{
				MySelectBox2.addItem();
		}
}
if (Utils.$('dst_goods_lists'))
{
		Utils.$('dst_goods_lists').ondblclick = function ()
		{
				MySelectBox2.delItem();
		}
}
//-->
</script>

<?php echo $this->fetch('pagefooter.htm'); ?>