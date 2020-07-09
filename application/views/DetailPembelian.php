

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=$pagetitle; ?>
        <a href="<?= base_url('pembelian/print/'.$id.'/'.$pembelians->pembelian_id_transaksi); ?>" class="btn btn-info">Print</a>
      </h1>
      <ol class="breadcrumb">
        <li class="active"><?=$pagetitle; ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="box">
        <div class="box-body">
            <h3>ID Transaksi : <?=$pembelians->pembelian_id_transaksi; ?></h3>
            <h3>Tanggal : <?=$pembelians->pembelian_tanggal; ?></h3>
            <h3>Supplier : <?=$pembelians->pembelian_supplier; ?></h3>
        <table id="table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Nama Obat</th>
                  <th>Jumlah</th>
                  <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                <?php 
		foreach($details as $detail){ 
		?>
		<tr>
			<td><?php echo $detail->detail_obat ?></td>
      <td><?php echo $detail->detail_jumlah ?></td>
      <td><?php echo $detail->detail_harga ?></td>
		</tr>
        <?php } ?>
        </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
