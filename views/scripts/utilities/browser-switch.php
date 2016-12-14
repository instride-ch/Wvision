<!DOCTYPE html>
<html lang="<?= $this->language; ?>">
    <head>
        <link type="text/css" rel="stylesheet" media="screen" href="/static/dist/css/main.min.css" />
        <style>
            i {
                font-size: 50px !important;
                margin-right: 20px;
            }
        </style>
    </head>
    <body>
		<div id="content">
			<div style="margin-left: -10px; margin-top: 70px; margin-bottom: 20px;">
                <img style="width:200px;" src="<?= $this->config->Logo; ?>" alt="logo" />
            </div>
			<div id="left">
				<address>
					<?= $this->config->Firma;?> <br />
					<?= $this->config->Adresse;?> <br />
					<?= $this->config->Plz;?> &nbsp;
					<?= $this->config->Ort;?> <br />
					<?= $this->config->Telefon;?> <br />
					<?= $this->config->Fax;?> <br />
					<a href="mailto:<?= $this->config->Email;?>"><?= $this->config->Email;?></a>
				</address>
			</div>
			<div id="right">
				<div id="browserinfo">
					<div class="error">
						<?= $this->t("Ihr Browser wird nicht unterstützt. Bitte installieren Sie die neueste Version einer der folgenden Browser."); ?>
					</div>
					<div class="links">
						<a href="http://www.google.com/chrome/" target="_blank"><i class="fa fa-chrome" aria-hidden="true"></i></a>
						<a href="http://www.mozilla.com/" target="_blank"><i class="fa fa-firefox" aria-hidden="true"></i></a>
						<a href="http://www.apple.com/safari/" target="_blank"><i class="fa fa-safari" aria-hidden="true"></i></a>
						<a href="http://www.microsoft.com/microsoft-edge" target="_blank"><i class="fa fa-edge" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>
    </body>
</html>
