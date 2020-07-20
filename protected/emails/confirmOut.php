<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?= $subject; ?></title>
        <style type="text/css">
            #outlook a{padding:0;}
            body{width:100% !important;} .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
            body{-webkit-text-size-adjust:none;} 

            body{margin:0; padding:0;}
            img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
            table td{border-collapse:collapse;}
            #backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}

            body, #backgroundTable{
                background-color:#FAFAFA;
            }

            #templateContainer{
		border: 1px solid #DDDDDD;
            }
			
            hr {
		margin-top: 0rem;
		margin-bottom: 0rem;
		border: 0;
		border-top: 1px solid rgba(0, 0, 0, 0.1);
            }
			
            .headerHR, .footerHR {
		width: 97%;
            }
			
            h1, .h1{
		color:#202020;
		display:block;
		font-family:Arial;
		font-size:34px;
		font-weight:bold;
		line-height:100%;
		margin-top:0;
		margin-right:0;
                margin-bottom:10px;
		margin-left:0;
		text-align:left;
            }

            h2, .h2{
		color:#202020;
		display:block;
		font-family:Arial;
		font-size:30px;
		font-weight:bold;
		line-height:100%;
		margin-top:0;
		margin-right:0;
		margin-bottom:10px;
		margin-left:0;
		text-align:left;
            }

            h3, .h3{
		color:#202020;
		display:block;
		font-family:Arial;
		font-size:26px;
		font-weight:bold;
		line-height:100%;
		margin-top:0;
		margin-right:0;
		margin-bottom:10px;
		margin-left:0;
		text-align:left;
            }

            h4, .h4{
		color:#202020;
            	display:block;
		font-family:Arial;
		font-size:22px;
		font-weight:bold;
		line-height:100%;
		margin-top:0;
		margin-right:0;
		margin-bottom:10px;
		margin-left:0;
		text-align:left;
            }

            #templateHeader{
		background-color:#FFFFFF;
		border-bottom:0;
            }

            .headerContent{
		color:#202020;
		font-family:Arial;
		font-size:34px;
		font-weight:bold;
		line-height:100%;
		padding:0.5rem 1rem 0.5rem 1rem;
		vertical-align:middle;
            }
			
            .leftColumn {
		text-align:left;
		width: 50%;
            }
			
            .rightColumn {
		text-align:right;
		font-weight: 400;
		font-size: 0.8rem;
		line-height: 1.5;
		vertical-align: middle;
            }
			
            .rightColumn a {
		text-decoration: none!important;
            }

            .headerContent a:link, .headerContent a:visited, .headerContent a .yshortcuts{
		color:#336699;
		font-weight:normal;
		text-decoration:underline;
            }

            img#headerImage {
		width: 35%;
            }

            #templateContainer, .bodyContent{
		background-color:#FFFFFF;
            }

            .bodyContent div{
		color:#505050;
		font-family:Arial;
		font-size:14px;
		line-height:150%;
		text-align:left;
            }

            .bodyContent div a:link, .bodyContent div a:visited, .bodyContent div a .yshortcuts{
		color:#336699;
		font-weight:normal;
		text-decoration:underline;
            }

            .templateButton{
		-moz-border-radius:3px;
		-webkit-border-radius:3px;
		background-color:#336699;
		border:0;
		border-collapse:separate !important;
		border-radius:3px;
            }

            .templateButton, .templateButton a:link, .templateButton a:visited, .templateButton a .yshortcuts{
		color:#FFFFFF;
		font-family:Arial;
		font-size:15px;
		font-weight:bold;
		letter-spacing:-.5px;
		line-height:100%;
                text-align:center;
		text-decoration:none;
            }

            .bodyContent img{
		display:inline;
		height:auto;
            }

            #templateFooter{
		background-color:#FFFFFF;
		border-top:0;
            }

            .footerContent div{
		color:#707070;
		font-family:Arial;
		font-size:12px;
		line-height:125%;
		text-align:center;
            }

            .footerContent div a:link, .footerContent div a:visited, .footerContent div a .yshortcuts{
		color:#336699;
		font-weight:normal;
		text-decoration:underline;
            }

            .footerContent img{
		display:inline;
            }

            #utility{
		background-color:#FFFFFF;
		border:0;
            }

            #utility div{
		text-align:center;
            }

            #monkeyRewards img{
		max-width:190px;
            }
			
            .btn-block {
		display: block;
		width: 100%;
		background-color: #336699;
		color: #fff!important;
		text-align: center;
		text-decoration: none!important;
		padding-top: 0.75rem;
		padding-bottom: 0.75rem;
            }

            .btn-block + .btn-block {
		margin-top: 0.5rem;
            }
            
            .fontSmall {
                font-size: 0.8rem;
            }
	</style>
    </head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <center>
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable">
                <tr>
                    <td align="center" valign="top" style="padding-top:20px;">
                    	<table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer">
                        	<tr>
                                    <td align="center" valign="top">
                                        <!-- Header block -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="templateHeader" class="headerContent">
                                            <tr>
                                                <td class="leftColumn">
                                                    <img src="data:image/png;base64,<?= base64_encode(file_get_contents(Yii::getPathOfAlias('assetsTheme').'/img/logo_new.png')); ?>" id="headerImage" />
                                                </td>
						<td class="rightColumn">
                                                    <a href="<?= Yii::app()->controller->createAbsoluteUrl('/');?>"><?= Yii::app()->controller->createAbsoluteUrl('/');?></a><br />
                                                    Na hutích 581/1, Dejvice, 160 00 Praha 6, Czech Republic<br />
                                                    Goldfort S.R.O <a href="mailto://<?= Yii::app()->params->supportEmail; ?>"><?= Yii::app()->params->supportEmail; ?></a><br />
                                                </td>
                                            </tr>
                                        </table>
					<hr class="headerHR"/>
                                        <!-- End header block -->
                                    </td>
                                </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Template body -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateBody">
                                    	<tr>
                                            <td valign="top">
                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top" class="bodyContent">
                                                            <div>
                                                                <h3 class="h4"><?= Yii::t('core', 'mail_confirmOutEmail_subject'); ?></h4>
								<br />
                                                                <p style="text-align: justify;">
                                                                    <?= Yii::t('core', 'mail_confirmOutEmail_introText', ['#summ' => $ext['summ']]); ?>
								</p>
								<table style="border: 1px solid #DDDDDD; width: 100%; padding: 1rem;">
                                                                    <tr>
									<td style="width: 40%" class="fontSmall"><strong><?= Yii::t('core', 'mail_defDate'); ?>:</strong></td>
									<td class="fontSmall"><?= date('d.m.Y H:i:s'); ?></td>
                                                                    </tr>
                                                                    <tr>
									<td class="fontSmall"><strong><?= Yii::t('core', 'mail_defIP'); ?>:</strong></td>
                                                                        <td  class="fontSmall"><?= Yii::app()->request->getUserHostAddress(); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="fontSmall"><strong><?= Yii::t('core', 'mail_activKey'); ?>:</strong></td>
									<td class="fontSmall"><?= $ext['activkey']; ?></td>
                                                                    </tr>
								</table>
								<a href="<?= Yii::app()->controller->createAbsoluteUrl('/finance/confirmout', ['order_id' => $ext['id'], 'activkey' => $ext['activkey']]); ?>" target="_blank" class="btn-block" style="margin-top: 1.5rem;"><?= Yii::t('core', 'mail_confirmOutEmail_btnOut'); ?></a>
                                                            </div>
							</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Template Body -->
                                </td>
                                </tr>
                        	<tr>
                                    <td align="center" valign="top">
                                        <!-- Footer block -->
                                	<hr class="footerHR" />
					<table border="0" cellpadding="10" cellspacing="0" width="600" id="templateFooter">
                                            <tr>
                                        	<td valign="top" class="footerContent">
                                                    <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td valign="top" style="padding-bottom: 0.2rem; padding-top: 0rem">
                                                                <div>
                                                                    <em>Copyright &copy; <?= date('Y').' '.Yii::app()->name;?>, All rights reserved.</em>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle" id="utility" style="padding-top: 0.2rem; padding-bottom: 0rem">
                                                                <div>
                                                                    <a href="<?= Yii::app()->controller->createAbsoluteUrl('/info/privacy'); ?>"><?= Yii::t('core', 'page_privacy'); ?></a> # 
                                                                    <a href="<?= Yii::app()->controller->createAbsoluteUrl('/info/terms'); ?>"><?= Yii::t('core', 'page_terms'); ?></a> # 
                                                                    <a href="<?= Yii::app()->controller->createAbsoluteUrl('/info/offer'); ?>"><?= Yii::t('core', 'page_offer'); ?></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- End footer block -->
                                    </td>
                                </tr>
                            </table>
                        <br />
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>