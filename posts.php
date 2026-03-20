<?php
    function renderizarPost($array, $curtidas, $salvos, $seguindo){
        
        ob_start(); ?>
        
        <?php 
        foreach($array as $valor){
            if(in_array($valor->id, $curtidas)){
                $fill_curtida = 'red';
                $stroke_curtida = 'red';
                $cor_span_curtida = 'red';
            }else{
                $fill_curtida = 'none';
                $stroke_curtida = 'rgb(110,110,110)';
                $cor_span_curtida = 'rgb(110,110,110)';
            }

            if(in_array($valor->id, $salvos)){
                $fill_salvo = '#1DA1F2';
                $stroke_salvo = '#1DA1F2';
            }else{
                $fill_salvo = 'none';
                $stroke_salvo = 'rgb(110,110,110)';
            }

            if(in_array($valor->idPerfil, $seguindo)){
                $button_text = 'Seguindo';
            }else{
                $button_text = 'Seguir';
            }
        ?>

        <div> 
            <div class="row mt-3 " style="border-bottom:1px solid rgb(110,110,110)">

                <div class="col-1">
                    <img src=<?=$valor->foto?> width="40px">
                </div>

                <div class="col-9" onclick="abrir_post($valor->id)">
                            
                    <span class="post-nome"><?=$valor->nome?></span>
                    <span class="post-arroba">@<?=$valor->nome?></span>

                    <p class="post-comentario"><?=$valor->texto?></p>
                                                
                    <? if($valor->midia != null){ ?>
                        <?if(strpos($valor->midia,'mp4') !== false){?>
                            <video src=<?='uploads/'.$valor->idPerfil.'/'.$valor->midia?> style="margin-bottom: 10px;" class="img-fluid" controls></video>
                        <?}else{?>
                            <img src=<?='uploads/'.$valor->idPerfil.'/'.$valor->midia?> style="margin-bottom: 10px;" class="img-fluid">
                        <?}?>
                    <?}?>
                                                
                                                                        
                    <div class="mt-0 mb-2 p-0">
                        <button id=<?='curtida_'.$valor->id?> class="curtidas" onclick="curtir(<?=$valor->id?>)">
                            <span>
                                <svg style="border-radius:20px; padding:5px;" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="<?=$fill_curtida?>" stroke="<?=$stroke_curtida?>" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 
                                            2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09 
                                            C13.09 3.81 14.76 3 16.5 3 
                                            19.58 3 22 5.42 22 8.5 
                                            c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>

                                <span style=<?='color:'.$cor_span_curtida?>><?=$valor->curtidas?></span>
                            </span>
                        </button>

                        <button id=<?='comentario_'.$valor->id?> class="comentarios" onclick="comentar(<?=$valor->id?>)">
                            <span>
                                <svg style="border-radius:20px;" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="-8 -8 40 40" fill="none" stroke="rgb(110,110,110)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> 
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>

                                <span><?=$valor->comentarios?></span>
                            </span>

                        </button>
                                                    

                        <button id=<?='salva_'.$valor->id?> class="salvar" onclick="salvar(<?=$valor->id?>)">
                            <span>
                                <svg style="border-radius:20px;" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="<?=$fill_salvo?>" viewBox="-8 -8 40 40" stroke="<?=$stroke_salvo?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                                </svg>
                            </span>
                        </button>

                    </div>

                </div>
                
                <? if($valor->idPerfil != $_SESSION['id']){?>
                    <div class="col-2">
                        <button class="btn btn-light botao-seguir <?='seguir_'.$valor->idPerfil?>" onclick="seguir(<?=$valor->idPerfil?>)"><?=$button_text?></button>
                    </div>
                <?}?>

            </div>
        </div>
        <?}?>
        
        <? return ob_get_clean();?>

<?}?>