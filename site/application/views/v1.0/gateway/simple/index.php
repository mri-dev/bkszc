<?php if ($this->gets[2] != 'ipn'): ?>

	<div class="otp-backref-data">
		<div class="otpay-bacref-holder <?=$this->class?>">
			<div class="wrapper">
				<div class="title-status">
					<h3><?=$this->title_status?></h3>
					<?php if ($this->simple_trans): ?>
					<div class="simple-trans-id">
						<?php echo $this->simple_trans; ?>
					</div>
					<?php endif; ?>
				</div>
				<?php if ($this->desc): ?>
				<div class="contdesc">
					<?php echo $this->desc; ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<a href="/cikkek/kategoriak/nemzeti-gundel-alapitvany"><< Vissza a támogatói oldalra</a>
	</div>
	<div class="opt-simple-info">
		<a href="http://simplepartner.hu/PaymentService/Fizetesi_tajekoztato.pdf" target="_blank">
		<img src="<?=SOURCE.'simplesdk/logos/simplepay_200x50.png'?>" title=" SimplePay - Online bankkártyás fi
		zetés" alt=" SimplePay vásárlói tájékoztató"></a>
	</div>
	<style media="screen">
		.opt-simple-info{
			position: relative;
		}
		.page-wrapper .pw {
			padding: 30px 0;
		}
		.opt-simple-info a{
			position: absolute;
	    display: block;
	    left: 50%;
	    top: 50%;
	    -webkit-transform: translate(-50%,-50%);
			transform: translate(-50%,-50%);
		}
		.otpay-bacref-holder{
			margin: 20px 0;
			background: #fdfdfd;
			padding: 20px;
			border: 1px solid #eae8e8;
			border-radius: 10px;
		}
		.otpay-bacref-holder .title-status{
			padding: 0 0 10px 0;
			margin: 0 0 10px 0;
			border-bottom: 1px solid #e6e6e6;
		}
		.otpay-bacref-holder .title-status h3{
			font-size: 1.4rem;
			font-weight: bold;
		}
		.otpay-bacref-holder.pay-success{
			border-top: 5px solid #2cc52c;
		}
		.otpay-bacref-holder.pay-success .title-status h3{
			color: #2cc52c;
		}

		.otpay-bacref-holder.pay-cancel,
		.otpay-bacref-holder.pay-timeout{
			border-top: 5px solid #e6a349;
		}
		.otpay-bacref-holder.pay-cancel .title-status h3,
		.otpay-bacref-holder.pay-timeout .title-status h3{
			color: #e6a349;
		}

		.otpay-bacref-holder.pay-fail{
			border-top: 5px solid #e64b49;
		}
		.otpay-bacref-holder.pay-fail .title-status h3{
			color: #e64b49;
		}


		.otpay-bacref-holder .contdesc{
			font-size: 0.9rem;
			line-height: 1.3;
			color: black;
		}
	</style>

<?php endif; ?>
