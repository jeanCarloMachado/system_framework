<?php
    global $endereco_site;
    $url_site=$endereco_site;

    $modelSite=new ACKsite_Model();
    $enderecoSite=$modelSite->listaEnderecos("1");
?>
					
						</table>
                    </td>
                </tr>
                <tr>
                    <td><img src="<?= $endereco_site ?>/imagens/email/shadow.png" alt="" width="600" height="40" border="0" style="display:block;" /></td>
                </tr>
                <tr>
                    <td style="text-align:center; padding:15px 0 15px 0; font-size:11px; color:#666;"><?= $enderecoSite["endereco_".$idioma]?>   <a href="mailto:<?= $enderecoSite["email_".$idioma] ?>" style="color:#666; text-decoration:none;"><?= $enderecoSite["email_".$idioma] ?></a><br />
                        <a href="<?= $endereco_site ?>" target="_blank" style="color:#666; text-decoration:none;"><?= $endereco_site ?></a></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>