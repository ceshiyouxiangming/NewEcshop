<?php if ($this->_var['full_page']): ?>
<!-- $Id: order_surplus_list.htm 14977 2008-10-22 03:25:17Z testyang $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>

<div class="form-div">
  <form action="javascript:searchUser()" name="searchForm">
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    &nbsp;<?php echo $this->_var['lang']['label_user_name']; ?> &nbsp;<input type="text" name="keyword" /> <input type="submit" value="<?php echo $this->_var['lang']['button_search']; ?>" />
  </form>
</div>

<form method="POST" action="" name="listForm">
<!-- start users list -->
<div class="list-div" id="listDiv">
<?php endif; ?>
<!--用户列表部分-->
<table cellpadding="3" cellspacing="1">
  <tr>
    <th><a href="javascript:listTable.sort('user_name'); "><?php echo $this->_var['lang']['username']; ?></a><?php echo $this->_var['sort_user_name']; ?></th>
    <th><a href="javascript:listTable.sort('order_sn'); "><?php echo $this->_var['lang']['order_sn']; ?></a><?php echo $this->_var['sort_order_sn']; ?></th>
    <th><?php echo $this->_var['lang']['surplus']; ?></th>
    <th><?php echo $this->_var['lang']['integral_money']; ?></th>
    <th><a href="javascript:listTable.sort('add_time'); "><?php echo $this->_var['lang']['add_time']; ?></a><?php echo $this->_var['sort_add_time']; ?></th>
    <th><?php echo $this->_var['lang']['handler']; ?></th>
  <tr>
  <?php $_from = $this->_var['order_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'order');if (count($_from)):
    foreach ($_from AS $this->_var['order']):
?>
  <tr align="center">
    <td class="first-cell"><?php echo htmlspecialchars($this->_var['order']['user_name']); ?></td>
    <td><?php echo $this->_var['order']['order_sn']; ?></td>
    <td><?php echo $this->_var['order']['surplus']; ?></td>
    <td><?php echo $this->_var['order']['integral_money']; ?></td>
    <td align="center"><?php echo $this->_var['order']['add_time']; ?></td>
    <td align="center">
      <a href="order.php?act=info&order_id=<?php echo $this->_var['order']['order_id']; ?>" title="<?php echo $this->_var['lang']['view_order']; ?>"><?php echo $this->_var['lang']['view']; ?></a>
    </td>
  </tr>
  <?php endforeach; else: ?>
  <tr><td class="no-records" colspan="10"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
  <tr>
      <td colspan="2">
      <input type="hidden" name="act" value="batch_remove" />
      <input type="submit" id="btnSubmit" value="<?php echo $this->_var['lang']['button_remove']; ?>" disabled="true" class="button" /></td>
      <td align="right" nowrap="true" colspan="8">
      <?php echo $this->fetch('page.htm'); ?>
      </td>
  </tr>
</table>

<?php if ($this->_var['full_page']): ?>
</div>
<!-- end users list -->
</form>
<script type="text/javascript" language="JavaScript">
<!--
listTable.recordCount = <?php echo $this->_var['record_count']; ?>;
listTable.pageCount = <?php echo $this->_var['page_count']; ?>;

<?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>


onload = function()
{
    document.forms['searchForm'].elements['keyword'].focus();
    // 开始检查订单
    startCheckOrder();
}

/**
 * 搜索用户
 */
function searchUser()
{
    listTable.filter['keywords'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
    listTable.filter['page'] = 1;
    listTable.loadList();
}
//-->
</script>

<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>