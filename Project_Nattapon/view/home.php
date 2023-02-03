<?php
    $sql = "SELECT * FROM post_tbl";
    $result = mysqli_query($conn, $sql);
?>

<section class = 'container mb-5'>
 <div class="row g-3 mt-3">    
    <?php while ($row = mysqli_fetch_assoc($result)) {
    ?>   
            <div class="col-md-3 col-lg-3">
                <div class="card" style="width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo $row['post_topic'];?></h3>
                        <!-- <p class="card-text"><?php echo $row['post_address'];?></p> -->
                        <a href="#" class="btn btn-primary">ดูรายละเอียด</a>
                    </div>
                </div>
            </div>
    <?php } ?>    
</div> 
</section>     