<div id="pagina-perfil">
    <div id="div-voltar" class="row">   

        <div class='col-2'>
            <button id="voltar" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <!-- Linha horizontal -->
                    <line x1="5" y1="12" x2="20" y2="12" />
                    <!-- Cabeça da seta -->
                    <polyline points="12 5 5 12 12 19" />
                </svg>
            </button>
        </div>
                        
        <div class='col-10  align-self-center'>
            <span style="color:#fff; font-size: 20px; font-weight:bolder"><?=$_POST['nome']?></span>
        </div>

    </div>

    <div class="row d-flex" style="border-bottom: 1px solid rgb(110,110,110);">
        <div style="background-color: rgb(40,40,40); height: 250px">

        </div>

        <div class="col-6 mb-0" id="teste">
            <div class="img-fluid">
                <img id="img-perfil" width="150px" src=<?=$_POST['foto']?>>
            </div>

            <div>
                <p class="paragrafo-nome-perfil"><?=$_POST['nome']?></p>
                <p class="paragrafo-arroba-perfil">@<?=$_POST['username']?></p>
            </div>
        </div>
            
        <div class="col-6 mt-3 d-flex justify-content-end">
            <div class="align-items-end">
                <button class="btn" id="editar-perfil">Editar perfil</button>   
            </div>                
        </div>

        <div class="row">
            <div id="posts-perfil" class="col-2 text-center">
                <p>Posts</p>
            </div>
        </div>
            
    </div>
</div>