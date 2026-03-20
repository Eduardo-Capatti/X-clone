    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="home.css">

    
<div id="editar-perfil-pagina"> 
    <div id="conteudo-editar-perfil" class="row">
        <form class="m-0 p-0">
            <div class="row p-0" style="width: 600px;border: 1px solid red;">
                <div class="col-8"  style="border: 1px solid red;">
                    <button id="botao-fechar" class="ms-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="3" x2="15" y2="15"/>
                            <line x1="15" y1="3" x2="3" y2="15"/>
                        </svg>
                    </button>

                    <p class="d-inline">Editar Perfil</p>
                </div>

                <div class="col-4">
                    <button type='submit' class="btn btn-light botao-salvar ms-auto d-block">Salvar</button>
                </div>

            </div>

            <div class="mx-auto mt-5" style="width: 500px;border: 1px solid red;">
                
                    <div style="position: relative" >
                        <div style="position: relative; top: 0px; width: 50px; height: 0px; opacity: 0;">
                            <input id="arquivo-perfil" type="file">
                        </div>

                        <div  style="position: relative; top:0px">
                            <img id="img-perfil-alterar" src="perfil.png"  width="150px" style="border: 1px solid red;">
                        </div>
                    </div>

                    <div class="form-floating">
                        <input id="nome" name="nome" class="form-control" type="text" placeholder="Nome">
                        <label id="label-nome" for="nome">Nome</label>
                    </div>
            
            </div> 
        </form> 

       
    </div>
</div>

 <script>
            button = document.getElementById("img-perfil-alterar")
            input = document.getElementById("arquivo-perfil")

            button.addEventListener("click", ()=>{
                console.log('olá')
                input.click()
            })
</script>