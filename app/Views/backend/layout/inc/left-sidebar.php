<div class="left-side-bar">
	<div class="brand-logo">
		<a href="index.html">
			<img src="/backend/vendors/images/deskapp-logo.svg" alt="" class="dark-logo" />
			<img src="/backend/vendors/images/deskapp-logo-white.svg" alt="" class="light-logo" />
		</a>
		<div class="close-sidebar" data-toggle="left-sidebar-close">
			<i class="ion-close-round"></i>
		</div>
	</div>
	<div class="menu-block customscroll">
		<div class="sidebar-menu">
			<ul id="accordion-menu">
				<li>
					<a href="<?= route_to('admin.home') ?>" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-home"></span><span class="mtext">Home</span>
					</a>
				</li>
				<li>
					<a href="<?= route_to('categories'); ?>" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-list"></span><span class="mtext">Categories and Products</span>
					</a>
				</li>
			
				<li>
					<div class="dropdown-divider"></div>
				</li>
				<li>
					<div class="sidebar-small-cap">Settings</div>
				</li>
				<li>
					<a href="<?= route_to('admin.profile');?>" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-user"></span>
						<span class="mtext">Profile</span>
					</a>
				</li>
				<li>
					<a href="" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-settings"></span>
						<span class="mtext">General</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>