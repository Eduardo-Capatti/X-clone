<div id="criar-conta"> 
    <div id="conteudo-criar-conta" class="row">

        <div class="col-12 d-flex p-0" style="width: 600px;">
            <button id="botao-fechar" class="ms-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="3" x2="15" y2="15"/>
                    <line x1="15" y1="3" x2="3" y2="15"/>
                </svg>
            </button>

            <div class="flex-fill text-center" style="margin-right: 30px;">
                <img width="30px" src="x-logo.png">
            </div>

        </div>

        <div class="mx-auto mt-5" style="width: 500px;">
            <h5>Criar sua conta</h5>

            <form>
                <div class="form-floating">
                    <input id="nome" name="nome" class="form-control" type="text" placeholder="Nome">
                    <label id="label-nome" for="nome">Nome</label>
                </div>

                <div class="form-floating">
                    <input id="senha" name="senha" class="form-control my-4" type="password" placeholder="Senha">
                    <label id="label-senha" for="senha">Senha</label>
                </div>

                <div class="form-floating">
                    <input id="celular-email" name="celular" class="form-control mb-3" type="text" placeholder="Celular">
                    <label id="label-alterna" for="celular-email">Celular</label>
                            
                    <p class="text-end mb-4"><span id="alternar-input" class="paragrafo-alterar-input" role="button">Usar o e-mail</span></p>
                </div>

                <h6>Data de nascimento</h6>
                <p style="font-size: 16px; margin-bottom: 20px;">Isso não será exibido publicamente. Confirme sua própria idade, mesmo se esta conta for de empresa, de um animal de estimação ou outros.</p>

                <div class="row">
                    <div class="col-6">
                        <div class="form-floating">

                            <select class="form-select" id="mes" name="mes">
                                <?php 
                                    $meses = ['', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                                    for ($i = 0; $i < 13; $i++){

                                    ?>
                                        <option value=<?=$i?>><?=$meses[$i]?></option>

                                    <?}?>
                            </select>
                            <label id="label-mes" for="mes">Mês</label>

                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-floating">
                            <select class="form-select" id="dia" name="dia">
                                <option value='0'></option>
                                    <?php 
                                        for ($i = 0; $i < 32; $i++){
                                            
                                    ?>
                                        <option value=<?=$i?>><?=$i?></option>
                                    <?}?>
                                        
                            </select>
                            <label id="label-dia" for="dia">Dia</label>
                                
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-floating">

                            <select class="form-select" id="ano" name="ano">
                                <option value='0'></option>
                                <?php 
                                    $ano = getdate();
                                    $ano = $ano['year'];
                                    $limite = $ano - 120;
                                    for ($ano; $ano >= $limite; $ano--){
                                            
                                ?>
                                    <option value=<?=$ano?>><?=$ano?></option>
                                <?}?>
                            </select>
                            <label id="label-ano" for="ano">Ano</label>

                        </div>
                    </div>

                </div>

                    <button type='submit' class="btn btn-light mt-5 botao-confirmar">Confirmar</button>
                    
                    <div>
            </form>
        </div>  
    </div>
</div>