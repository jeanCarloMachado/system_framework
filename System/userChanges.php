<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="format-detection" content="telephone=no" />
<title>Comunicado Importante</title>
</head>
<body style="margin:0; padding:0;">
<table width="100%" border="0" cellspacing="30" cellpadding="0" bgcolor="white" style="-webkit-text-size-adjust:none; background-color:#FFFFFF; font-size:11px; line-height:15px; color:#666; text-align:left; font-family:Arial, Helvetica, sans-serif;">
    <tr>
        <td align="center">
            <table width="600" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="35"></td>
                </tr>
                <tr>
                    <td style="text-align:center;"><img src="'.$endereco_site.'/imagens/email/logo.png" alt="" border="0" style="vertical-align:bottom;" /></td>
                </tr>
                <tr>
                    <td height="5"></td>
                </tr>
                <tr>
                    <td><img src="'.$endereco_site.'/imagens/email/shadow.png" alt="" width="600" height="40" border="0" style="display:block;" /></td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:45px 45px 25px 45px; text-align:left;">';
                        	
                        	<p>O usuário <strong><?= $old['razao_soc'] ?></strong> modificou os seguintes dados: </p>

                        	<ul>
                        	<?php 
                        		foreach($new as $columnId => $newColumn) {
                        	?>
                        			<li><strong><?= ucfirst($columnId) ?></strong> Atual: <?= $newColumn ?><br/> Antigo: <?= (!empty($old[$columnId])) ? $old[$columnId] : "Não informado" ?></li>
                        	<?php
                        		}
							?>
							</ul>
	
						</table>
                    </td>
                </tr>
                <tr>
                    <td><img src="'.$endereco_site.'/imagens/email/shadow.png" alt="" width="600" height="40" border="0" style="display:block;" /></td>
                </tr>
                <tr>
                    <td style="text-align:center; padding:15px 0 15px 0; font-size:11px; color:#666;">' <a href="mailto:'.$enderecoSite["email_".$idioma].'" style="color:#666; text-decoration:none;"></a><br />
                        <a href="'.$endereco_site.'" target="_blank" style="color:#666; text-decoration:none;"></a></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
