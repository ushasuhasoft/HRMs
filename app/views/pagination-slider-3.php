<?php
	$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
?>

<?php if ($paginator->getTotal() > 0): ?>
 <div class="dataTables_info" id="dyntable_info">
	<?php echo "Results:  ".$paginator->getFrom().' - '. $paginator->getTo() .' of '. '<strong>'. $paginator->getTotal() .'</strong>' ?>
<?php endif; ?>

<?php if ($paginator->getTotal() > 0): ?>
<ul class="pagination pull-right">
	<?php echo $presenter->render(); ?>
</ul>
</div>
<?php endif; ?>