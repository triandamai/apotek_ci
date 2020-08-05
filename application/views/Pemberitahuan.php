

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=$pagetitle; ?>
       
      </h1>
      <ol class="breadcrumb">
        <li class="active"><?=$pagetitle; ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php if($notifikasi != null){
      foreach($notifikasi as $n){?>
      <div class="box">
            <div class="box-title"><h4><p><?= $n['status'] ?></p></h4></div>
            <div class="box-body">
            <?= $n['message']?>
            </div>
      </div>
    <?php 
    }
  }?>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
