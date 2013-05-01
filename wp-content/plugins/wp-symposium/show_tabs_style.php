<style>
	.wrap #mail-main {
		background-color: #bfbfff !important;
	}
	
	.wrap #mail_tabs {
		width: 100%;
		border-radius:0px;
		-moz-border-radius:0px;
		margin-left: 10px;
		overflow: auto;
		position: relative;
		top: 1px;
	}

	.wrap .mail_tab {
		border: 1px solid #666;
		padding: 3px;
		border-radius:0px;
		-moz-border-radius:0px;
	 	border-top-left-radius:5px;
		-moz-border-radius-topleft:5px;
	 	border-top-right-radius:5px;
		-moz-border-radius-topright:5px;
		width: 53px;
		text-align: center;
		float: left;
		margin-right: 1px;
	}
	
	.wrap #mail_tabs .nav-tab-active {
		z-index: 3;
		border-bottom: 1px solid #bfbfff;
		background-color: #bfbfff;
	}

	.wrap #mail_tabs .nav-tab-inactive {
		z-index: 1;
		border-bottom: 1px solid #666;
		background-color: #efefef;
		background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#ccc), to(#fff));
		background: -webkit-linear-gradient(top, #fff, #ccc);
		background: -moz-linear-gradient(top, #fff, #ccc);
		background: -ms-linear-gradient(top, #fff, #ccc);
		background: -o-linear-gradient(top, #fff, #ccc);
	}

	.wrap #mail_tabs .nav-tab-active-link {
		text-decoration: none;
		color: #000;
		font-size: 12px;
	}

	.wrap #mail_tabs .nav-tab-inactive-link {
		text-decoration: none;
		color: #666;
		font-size: 12px;
	}

	.wrap #mail-main {
		z-index: 2;
		width: 98%;
		border-radius: 5px;
		-moz-border-radius:5px;
		border: 1px solid #666;
		background-color: #fff;
		padding: 10px;
		overflow: auto;
		margin-bottom: 15px;
	}
	
	.wrap .highlighted_row {
		background-color: #cfcfff;
	}

</style>	