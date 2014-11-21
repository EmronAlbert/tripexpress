 <?php $page = $this->uri->segment(2); ?>

 <div class="col-sm-2 col-md-1 sidebar">
          <ul class="nav nav-sidebar">
            <li <?php if ($page == "dashboard" || $page == ''){ echo "class='active'";} ?> >
              <a href="<?php echo base_url() ?>admin/dashboard">
                <div class="nav-icon"><span class="icon-home"></span></span></div>
                <div class="nav-title">Dashboard</div>
              </a>
            </li>
            <li <?php if ($page == "bookings"){ echo "class='active'";} ?>>
              <a href="<?php echo base_url() ?>admin/bookings">
                <div class="nav-icon"><span class="icon-book"></i></span></div>
                <div class="nav-title">Bookings</div>
              </a>
            </li>
            <li <?php if ($page == "members"){ echo "class='active'";} ?>>
              <a href="<?php echo base_url() ?>admin/members">
                <div class="nav-icon"><span class="icon-user-3"></span></div>
                <div class="nav-title">Members</div>
              </a>
            </li>
            <li <?php if ($page == "destinations"){ echo "class='active'";} ?>>
              <a href="<?php echo base_url() ?>admin/destinations">
                <div class="nav-icon"><span class="icon-location"></span></div>
                <div class="nav-title">Destinations</div>
              </a>
            </li>
            <li <?php if ($page == "tours"){ echo "class='active'";} ?>>
              <a href="<?php echo base_url() ?>admin/tours">
                <div class="nav-icon"><span class="icon-calendar"></span></div>
                <div class="nav-title">Tours</div>
              </a>
            </li>
            <li <?php if ($page == "clients"){ echo "class='active'";} ?>>
              <a href="<?php echo base_url() ?>admin/clients">
                <div class="nav-icon"><span class="icon-user-2"></span></div>
                <div class="nav-title">Clients</div>
              </a>
            </li>
          </ul>                 
        </div> 