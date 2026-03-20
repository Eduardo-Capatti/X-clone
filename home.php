<?php
    session_start();
    
    if(isset($_SESSION['id'])){
        require_once ('model.php');
        $_SESSION['ids-post'] = [];

        $info = $bd->buscar_info($_SESSION['id']);
        $info = $info[0];
    }else{
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página inicial / X</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="home.css">
</head>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>

        function listar(){
            $.ajax({
                type: 'POST',
                url: 'model.php?listar=true',
                dataType: 'json',
                success: (dados)=>{
                    $('#posts').html(dados.dados)
                },
                error: (xhr, status, error) => {
                    console.error('Erro:', xhr.responseText);
                    console.error('Status:', status);
                    console.error('Detalhe:', error);
                }
            })
        
        }

        function curtir(idPost){
            $.ajax({
                    type: 'POST',
                    url: 'model.php?curtir=true',
                    data:{
                        idPost: idPost
                    },
                    dataType: 'json',
                    success: (dados)=>{
                        
                        if(dados.status == 'colocou'){
                            $('#curtida_'+idPost + ' span svg').attr('fill', 'red')
                            $('#curtida_'+idPost + ' span svg').attr('stroke', 'red')

                            let controlador = parseInt($('#curtida_'+idPost + ' span span').html())

                            $('#curtida_'+idPost + ' span span').html(controlador + 1)
                            $('#curtida_'+idPost + ' span span').css('color','red')
                        }

                        if(dados.status == 'tirou'){
                            $('#curtida_'+idPost + ' span svg').attr('fill', 'none')
                            $('#curtida_'+idPost + ' span svg').attr('stroke', 'rgb(110,110,110)')

                            let controlador = parseInt($('#curtida_'+idPost + ' span span').html())

                            $('#curtida_'+idPost + ' span span').html(controlador - 1)
                            $('#curtida_'+idPost + ' span span').css('color','rgb(110,110,110)')
                        }
                        
                        
                    },
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                })
        }

        function salvar(idPost){
            $.ajax({
                    type: 'POST',
                    url: 'model.php?salvar=true',
                    data:{
                        idPost: idPost
                    },
                    dataType: 'json',
                    success: (dados)=>{
                        
                        if(dados.status == 'colocou'){
                            $('#salva_'+idPost + ' span svg').attr('fill', '#1DA1F2')
                            $('#salva_'+idPost + ' span svg').attr('stroke', '#1DA1F2')
                        }

                        if(dados.status == 'tirou'){
                            $('#salva_'+idPost + ' span svg').attr('fill', 'none')
                            $('#salva_'+idPost + ' span svg').attr('stroke', 'rgb(110,110,110)')
                        }
                        
                        
                    },
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                })
        }

        function seguir(idPerfil){
            $.ajax({
                    type: 'POST',
                    url: 'model.php?seguir=true',
                    data:{
                        idPerfil: idPerfil
                    },
                    dataType: 'json',
                    success: (dados)=>{
                        
                        if(dados.status == 'colocou'){
                            $('.seguir_'+idPerfil).html('Seguindo')
                        }

                        if(dados.status == 'tirou'){
                            $('.seguir_'+idPerfil).html('Seguir')
                        }
                        
                        
                    },
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
            })
        }

        var idPost = 0
        function comentar(id){
            $.post('responder.php',{foto:'<?=$info->foto?>'}, (data) =>{
                $('body').prepend(data)
            })   
            idPost = id  
        }

        function postar_comentario(){
            $(document).off('click', '.botao-postar-comentario')

            $(document).on('click', '.botao-postar-comentario',()=>{
                let dados = $('#form-responder').serialize()

                dados += '&idPost=' + idPost
                
                $.ajax({
                    type: 'POST',
                    url: 'model.php?postar-comentario=true',
                    data: dados,
                    success: ()=>{
                        $('#div-responder').remove()
                        
                        let numeroComentario = parseInt($('#comentario_'+idPost + ' span span').html()) + 1
                        $('#comentario_'+idPost + ' span span').html(numeroComentario)
                    },
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                })
            })
        }

        function abrir_post(id){
           
        } 


        $(document).ready(()=>{

            $('body').on('click', 'a', (e)=>{
                e.preventDefault()
            })

            $('body').on('click', 'button', (e)=>{
                e.preventDefault()
            })


            $('#midia').on('change', ()=>{
                
                let botao = document.querySelector('#botao-fechar-imagem-video')
                botao.style = 'display: inline'

                $('#imagem-video img').remove()
                $('#imagem-video video').remove()
                let divPai = document.querySelector('#imagem-video')
            
                let midia = document.querySelector('#midia')
                let formatos = ['image/png', 'image/jpg', 'image/jpeg']

                if(formatos.includes(midia.files[0].type)){
                    let img = document.createElement('img')
                    img.className = 'img-fluid'
                    img.src = URL.createObjectURL(midia.files[0])
                    divPai.appendChild(img)
                }else if(midia.files[0].type.includes('video/mp4')){
                    let video = document.createElement('video')
                    video.className = 'img-fluid'
                    video.src = URL.createObjectURL(midia.files[0])
                    video.controls = true
                    divPai.appendChild(video)
                }
            })

            $('#botao-fechar-imagem-video').on('click', ()=>{
                let botao = document.querySelector('#botao-fechar-imagem-video')
                botao.style = 'display: none'

                $('#imagem-video img').remove()
                $('#imagem-video video').remove()
                let midia = document.querySelector('#midia')
                midia.value = null
            })


            $('.botao-postar').on('click', ()=>{
                let dados = new FormData(document.querySelector('#form-mensagem'))
                $.ajax({
                    type: 'POST',
                    url: 'model.php?postar=true',
                    data: dados,
                    dataType: 'json',
                    processData: false, 
                    contentType: false, 
                    success: (dados)=>{
                        
                        let botao = document.querySelector('#botao-fechar-imagem-video')
                        botao.style = 'display: none'

                        $('#imagem-video img').remove()
                        $('#imagem-video video').remove()
                        let midia = document.querySelector('#midia')
                        midia.value = null

                        let textarea = document.querySelector('#mensagem')
                        textarea.value = ''

                        
                        //Mostrando o novo post do usuário na parte superior da div posts
                        $('#posts').prepend(dados.dados);                        

                    },
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                })
            })


            $('body').on('keydown', '#busca input', (e)=>{

                let input = document.querySelector('#buscar')

                if(e.key == "Enter" && input.value == ''){
                    e.preventDefault()
                }

                if(e.key == "Enter" && input.value != ''){
                    e.preventDefault()

                    let dados = $('#form-busca').serialize()

                    $.ajax({
                        type: 'POST',
                        url: 'model.php?buscar=true',
                        data: dados,
                        dataType: 'json',
                        success: (dados)=>{
                            $('#posts').html(dados.dados)
                            input.value = ''
                           
                        },
                        error: (xhr, status, error) => {
                            console.error('Erro:', xhr.responseText);
                            console.error('Status:', status);
                            console.error('Detalhe:', error);}
                    })
                }
                


            })
            

            $('body').on('click','#botao-enquete', ()=>{
                $('#mensagem').attr('placeholder', 'Fazer uma pergunta')
                $('#input-opcoes').removeClass('d-none')
                $('#opcao1').focus()
            })

            $('body').on('click', '#remover-enquete', ()=>{
                $('#input-opcoes').addClass('d-none')
                $('#mensagem').attr('placeholder', 'O que está acontecendo?')
                $('#botao-opcao2').removeClass('d-none')
                $('#input-opcoes .row .div-que-altera-col').removeClass('col-12')
                $('#input-opcoes .row .div-que-altera-col').addClass('col-10')
                $('#div-opcao3').addClass('d-none')
                $('#div-opcao4').addClass('d-none')
            })

            $('body').on('click', '#botao-opcao2', ()=>{
                $('#botao-opcao2').addClass('d-none')
                $('#botao-opcao3').removeClass('d-none')
                $('#div-opcao3').removeClass('d-none')
                $('#opcao3').focus()
            })

            $('body').on('click', '#botao-opcao3', ()=>{
                $('#botao-opcao3').addClass('d-none')
                $('#div-opcao4').removeClass('d-none')
                $('#input-opcoes .row .div-que-altera-col').removeClass('col-10')
                $('#input-opcoes .row .div-que-altera-col').addClass('col-12')
                $('#opcao4').focus()
            })

            $('body').on('focus', '#opcao1', ()=>{
                $('#label-opcao1').addClass('onclick-label')
            })

            $('body').on('blur', '#opcao1', ()=>{
                $('#label-opcao1').removeClass('onclick-label')
            })

            $('body').on('focus', '#opcao2', ()=>{
                $('#label-opcao2').addClass('onclick-label')
            })

            $('body').on('blur', '#opcao2', ()=>{
                $('#label-opcao2').removeClass('onclick-label')
            })

            $('body').on('focus', '#opcao3', ()=>{
                $('#label-opcao3').addClass('onclick-label')
            })

            $('body').on('blur', '#opcao3', ()=>{
                $('#label-opcao3').removeClass('onclick-label')
            })

            $('body').on('focus', '#opcao4', ()=>{
                $('#label-opcao4').addClass('onclick-label')
            })

            $('body').on('blur', '#opcao4', ()=>{
                $('#label-opcao4').removeClass('onclick-label')
            })

            let html = ''

            setInterval(()=>{

                if(divSelecionada == 'para-voce'){
                    $.ajax({
                    type: 'POST',
                    url: 'model.php?buscar_novos_posts=true',
                    dataType: 'json',
                    success: (dados)=>{
                        if(dados.quantidade > 0){
                            $('#span-quantidade').html('Mostrar ' + dados.quantidade)
                            $('#quantidade-posts-novos').removeClass('d-none')

                            html = dados.dados
                        }
                        
                    },
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                    })
                }

                if(divSelecionada == 'seguindo'){
                    $.ajax({
                    type: 'POST',
                    url: 'model.php?buscar_novos_posts_seguindo=true',
                    dataType: 'json',
                    success: (dados)=>{
                        if(dados.quantidade > 0){
                            $('#span-quantidade').html('Mostrar ' + dados.quantidade)
                            $('#quantidade-posts-novos').removeClass('d-none')

                            html = dados.dados
                        }
                        
                    },
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                    })
                }
                
            }, 3000)

            $('#quantidade-posts-novos').on('click', ()=>{
                $('#posts').prepend(html)
                $('#quantidade-posts-novos').addClass('d-none')

                $.ajax({
                    type: 'POST',
                    url: 'model.php?retornar_id_max=true',
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                })
            })

            $('body').on('click', (e)=>{
                let divResponder = document.querySelector('#conteudo-div-responder')
                if(divResponder != null && !divResponder.contains(e.target)){
                    $('#div-responder').remove()
                }
            })

            let pagina = 'home'

            $('#itensSalvos').on('click', ()=>{

                $.ajax({
                    type: 'POST',
                    url: 'model.php?buscar-itensSalvos=true',
                    dataType: 'json',
                    success: (dados)=>{
                        if(pagina != 'itensSalvos'){
                            $('#estrutura-sem-posts').addClass('d-none')

                            if(dados.dados == 0){
                                $('#posts').html("<p class='text-center mt-3'>Nenhum post encontrado</p>")
                            }else{
                                $('#posts').html(dados.dados)
                            }
                            
                            
                            $.post('voltar.html', (data)=>{
                                $('#fazer-post').prepend(data)
                            })

                            pagina = 'itensSalvos'

                            $('#home svg').attr('fill', 'none')
                            $('#home span').css('font-weight', 'normal')

                            $('#explorar svg').attr('stroke-width', '2')
                            $('#explorar span').css('font-weight', 'normal')

                            $('#itensSalvos svg').attr('fill', 'white')
                            $('#itensSalvos span').css('font-weight', 'bolder')

                            $('#perfil-pagina svg').attr('fill', 'none')
                            $('#perfil-pagina span').css('font-weight', 'normal')

                            $('#busca').remove()
                            $('#pagina-perfil').remove()

                        }
                       
                    },
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                })
            })

            $('#home').on('click', ()=>{
                $.ajax({
                    type: 'POST',
                    url: 'model.php?listar=true',
                    dataType: 'json',
                    success: (dados)=>{
                        if(pagina != 'home'){
                            $('#quantidade-posts-novos').addClass('d-none')

                            $('#estrutura-sem-posts').removeClass('d-none')

                            $('#posts').html(dados.dados)
                            pagina = 'home'

                            $('#itensSalvos svg').attr('fill', 'none')
                            $('#itensSalvos span').css('font-weight', 'normal')

                            $('#explorar svg').attr('stroke-width', '2')
                            $('#explorar span').css('font-weight', 'normal')

                            $('#home svg').attr('fill', 'white')
                            $('#home span').css('font-weight', 'bolder')

                            $('#perfil-pagina svg').attr('fill', 'none')
                            $('#perfil-pagina span').css('font-weight', 'normal')

                            $('#div-voltar').remove()
                            $('#busca').remove()
                            $('#pagina-perfil').remove()
                        }
                    },
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                })
            })

            $('#explorar').on('click', ()=>{
                if(pagina != 'explorar'){
            
                    $('#itensSalvos svg').attr('fill', 'none')
                    $('#itensSalvos span').css('font-weight', 'normal')

                    $('#home svg').attr('fill', 'none')
                    $('#home span').css('font-weight', 'normal')

                    $('#explorar svg').attr('stroke-width', '5')
                    $('#explorar span').css('font-weight', 'bolder')

                    $('#perfil-pagina svg').attr('fill', 'none')
                    $('#perfil-pagina span').css('font-weight', 'normal')

                    $('#div-voltar').remove()
                    
                    $.post('busca.html', (dados)=>{
                        $('#fazer-post').prepend(dados)
                    })

                    $('#estrutura-sem-posts').addClass('d-none')

                    $('#posts').html('')
                    $('#pagina-perfil').remove()


                    pagina = 'explorar'
                }
            })

            $('#perfil-pagina').on('click', ()=>{
                if(pagina != 'perfil'){
                    $.ajax({
                    type: 'POST',
                    url: 'model.php?listar-perfil=true',
                    dataType: 'json',
                    success: (dados)=>{
                        $('#itensSalvos svg').attr('fill', 'none')
                        $('#itensSalvos span').css('font-weight', 'normal')

                        $('#home svg').attr('fill', 'none')
                        $('#home span').css('font-weight', 'normal')

                        $('#explorar svg').attr('stroke-width', '2')
                        $('#explorar span').css('font-weight', 'normal')

                        $('#perfil-pagina svg').attr('fill', 'white')
                        $('#perfil-pagina span').css('font-weight', 'bolder')

                        $('#div-voltar').remove()
                        $('#busca').remove()

                        $.post('perfil.php', {foto: "<?=$info->foto?>", nome: "<?=$info->nome?>", username: "<?=$info->username?>"}, (dados)=>{
                            $('#fazer-post').prepend(dados)
                        })

                        $('#estrutura-sem-posts').addClass('d-none')

                        if(dados.dados == 0){
                            $('#posts').html("<p class='text-center mt-3'>Nenhum post até o momento!</p>")
                        }else{
                            $('#posts').html(dados.dados)
                        }
                        

                        pagina = 'perfil'
                    },
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                    })
                    
                }
            })


            $('body').on('click','#voltar',()=>{
                $.ajax({
                    type: 'POST',
                    url: 'model.php?listar=true',
                    dataType: 'json',
                    success: (dados)=>{
                            $('#quantidade-posts-novos').addClass('d-none')
                            
                            $('#estrutura-sem-posts').removeClass('d-none')

                            $('#posts').html(dados.dados)
                            pagina = 'home'

                            $('#itensSalvos svg').attr('fill', 'none')
                            $('#itensSalvos span').css('font-weight', 'normal')

                            $('#home svg').attr('fill', 'white')
                            $('#home span').css('font-weight', 'bolder')

                            
                            $('#perfil-pagina svg').attr('fill', 'none')
                            $('#perfil-pagina span').css('font-weight', 'normal')

                            $('#div-voltar').remove()
                    },
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                })
            })

            $('body').on('click','#div-voltar',()=>{
                $.ajax({
                    type: 'POST',
                    url: 'model.php?listar=true',
                    dataType: 'json',
                    success: (dados)=>{
                            $('#quantidade-posts-novos').addClass('d-none')
                            
                            $('#estrutura-sem-posts').removeClass('d-none')

                            $('#posts').html(dados.dados)
                            pagina = 'home'

                            $('#itensSalvos svg').attr('fill', 'none')
                            $('#itensSalvos span').css('font-weight', 'normal')

                            $('#home svg').attr('fill', 'white')
                            $('#home span').css('font-weight', 'bolder')

                            $('#perfil-pagina svg').attr('fill', 'none')
                            $('#perfil-pagina span').css('font-weight', 'normal')

                            $('#pagina-perfil').remove()
                    },
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                })
            })

            let divSelecionada = 'para-voce'
            
            $('#div-para-voce').on('click', ()=>{
                $.ajax({
                    type: 'POST',
                    url: 'model.php?listar=true',
                    dataType: 'json',
                    success: (dados)=>{
                        if(divSelecionada == 'seguindo'){
                            $('#posts').html(dados.dados)
                            divSelecionada = 'para-voce'
                            
                            $('#div-seguindo').removeClass('div-para-voce-ou-seguindo')
                            $('#div-para-voce').addClass('div-para-voce-ou-seguindo')
                        }
                        
                    },
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                })
            })

            $('#div-seguindo').on('click', ()=>{
                $.ajax({
                    type: 'POST',
                    url: 'model.php?listar-seguindo=true',
                    dataType: 'json',
                    success: (dados)=>{
                        if(divSelecionada == 'para-voce'){
                            if(dados.dados == 0){
                                $('#posts').html('<p class="text-center">Nenhum conteúdo foi encontrado</p>')
                            }else{
                                $('#posts').html(dados.dados)
                            }
                            
                            divSelecionada = 'seguindo'

                            $('#div-seguindo').addClass('div-para-voce-ou-seguindo')
                            $('#div-para-voce').removeClass('div-para-voce-ou-seguindo')
                        }
                        
                    },
                    error: (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                })
            })
        })

    </script>

<body onload="listar()">
 
    <div class="container">
        
        <div class="row">
            <div class='col-3 mt-3'>
                <nav>

                    <div class="ms-5">
                        <img src="x-logo.png" width="50px" style="padding: 10px;">

                        <ul class="nav flex-column">
                            <li class="nav-item">
                                
                                <a id="home" class="nav-link" href="#">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 9L12 2l9 7v11a2 2 0 0 1-2 2h-5v-7H10v7H5a2 2 0 0 1-2-2z"/>
                                    </svg>

                                    <span style="font-weight:bold">Página Inicial</span>
                                </a>
                            </li> <!--1-->

                            <li class="nav-item">
                                <a id="explorar" class="nav-link" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"/>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                    </svg>
                                    <span class="align-self-center">Explorar</span>
                                </a>
                            </li><!--1-->

                            <li class="nav-item">
                                <a id="itensSalvos" class="nav-link" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                                    </svg>

                                    <span>Itens Salvos</span>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a id="perfil-pagina" class="nav-link" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="7" r="4"/>
                                        <path d="M5.5 21a7.5 7.5 0 0 1 13 0"/>
                                    </svg>

                                    <span>Perfil</span>
                                </a>
                            </li><!--2-->

                            <button class="btn btn-light botao-nav">Postar</button>

                            <div id="perfil" class="row align-self-center">
                                
                                <div class="col-2 align-self-center">
                                    <img src=<?=$info->foto?> width="40px"> 
                                </div>

                                <div class="col-8">
                                    <p class="paragrafo-nome"><?=$info->nome?></p>
                                    <p class="paragrafo-arroba">@<?=$info->username?></p>
                                </div>

                                <div class="col-2 align-self-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white">
                                        <circle cx="5" cy="12" r="2"/>
                                        <circle cx="12" cy="12" r="2"/>
                                        <circle cx="19" cy="12" r="2"/>
                                    </svg>
                                </div>

                            </div>

                        </ul>
                    
                    </div>

                </nav>
            </div>

            <div id="fazer-post" class="col-6">
                

                <div id="estrutura-sem-posts">
                    <div class="row text-center sticky-top opcao-top">
                        
                        <div id="div-para-voce" class="col-6 d-flex align-items-center justify-content-center para-conteudo div-para-voce-ou-seguindo">
                                Para você 
                        </div>
                        
                        <div id="div-seguindo" class="col-6 d-flex align-items-center justify-content-center para-conteudo">
                                Seguindo
                        </div>
                        
                    </div>

                    <div>
                        <div class="row mt-3" style="border-bottom: 1px solid rgb(110,110,110);">

                            <div class="col-1">
                                <img src=<?=$info->foto?> width="40px">
                            </div>

                            <div class="col-11">
                                <form id="form-mensagem" enctype="multipart/form-data">
                                    <textarea name="mensagem" id="mensagem" placeholder="O que está acontecendo?"></textarea>

                                    <div id = "imagem-video">
                                        <button id='botao-fechar-imagem-video'>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="3" y1="3" x2="15" y2="15"/>
                                                <line x1="15" y1="3" x2="3" y2="15"/>
                                            </svg>
                                        </button>
                                    </div>


                                    <div id='input-opcoes' class='d-none'>
                                        <div style='padding:15px'>
                                            <div class="row mb-3">
                                                <div class="col-10 div-que-altera-col">
                                                    <div class='form-floating'>
                                                        <input id="opcao1" name="opcao1" class="form-control" type="text" placeholder="Opcao 1">
                                                        <label id="label-opcao1" for="opcao1">Opcao 1</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-10 div-que-altera-col">
                                                    <div class='form-floating'>
                                                        <input id="opcao2" name="opcao2" class="form-control" type="text" placeholder="Opcao 2">
                                                        <label id="label-opcao2" for="opcao2">Opcao 2</label>
                                                    </div>
                                                </div>

                                                <div class='col-2 align-self-center'>
                                                    <button id='botao-opcao2' class="botao-icones">
                                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0,0,256,256">
                                                            <g fill="#1da1f2" fill-rule="evenodd" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(10.66667,10.66667)"><path d="M11,2v9h-9v2h9v9h2v-9h9v-2h-9v-9z"></path></g></g>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <div id="div-opcao3" class="row d-none mb-3">
                                                <div class="col-10 div-que-altera-col">
                                                    <div class='form-floating'>
                                                        <input id="opcao3" name="opcao3" class="form-control" type="text" placeholder="Opcao 3">
                                                        <label id="label-opcao3" for="opcao2">Opcao 3</label>
                                                    </div>
                                                </div>

                                                <div class='col-2 align-self-center'>
                                                    <button id='botao-opcao3' class="botao-icones">
                                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0,0,256,256">
                                                            <g fill="#1da1f2" fill-rule="evenodd" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(10.66667,10.66667)"><path d="M11,2v9h-9v2h9v9h2v-9h9v-2h-9v-9z"></path></g></g>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <div id="div-opcao4" class="row d-none mb-3">
                                                <div class="col-10 div-que-altera-col">
                                                    <div class='form-floating'>
                                                        <input id="opcao4" name="opcao4" class="form-control" type="text" placeholder="Opcao 4">
                                                        <label id="label-opcao4" for="opcao4">Opcao 4</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="remover-enquete">
                                            Remover enquete
                                        </div>

                                    </div>


                                    <hr style="margin-bottom:10px; color: rgb(200,200,200);">

                                    <div class="row mb-2">
                                        <div class="col-6 align-self-center">
                                            <span class="conteudo-botao"><!--botao midia-->
                                                <input type="file" id="midia" name="midia" accept="image/*, video/*">
                                                <label for="midia" class='botao-icones'>
                                                    
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="#1DA1F2" width="25">
                                                        <path d="M25.65 4H6.35A4.35 4.35 0 0 0 2 8.35v15.3A4.35 4.35 0 0 0 6.35 28h19.3A4.35 4.35 0 0 0 30 23.65V8.35A4.35 4.35 0 0 0 25.65 4zM28 23.65A2.36 2.36 0 0 1 25.65 26H6.35A2.36 2.36 0 0 1 4 23.65V8.35A2.36 2.36 0 0 1 6.35 6h19.3A2.36 2.36 0 0 1 28 8.35z"/>
                                                        <path d="M21.53 12.85a2.21 2.21 0 0 0-4-.12l-4.37 8.32-.9-1.33a2.19 2.19 0 0 0-3.46-.24l-2.54 2.85a1 1 0 1 0 1.48 1.34l2.57-2.86a.18.18 0 0 1 .17-.06.18.18 0 0 1 .15.09l1.84 2.72a1 1 0 0 0 .88.44 1 1 0 0 0 .84-.54l5.15-9.8a.18.18 0 0 1 .18-.11.17.17 0 0 1 .18.12l4.39 9.74A1 1 0 0 0 25 24a1 1 0 0 0 .41-.09 1 1 0 0 0 .5-1.32zM10 16a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm0-6a2 2 0 1 1-2 2 2 2 0 0 1 2-2z"/>
                                                    </svg>

                                                </label>
                                            </span>

                                            <button id="botao-enquete" class="botao-icones"><!--botao enquete-->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="20" fill="none" stroke="#1DA1F2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="5" cy="6" r="1"/>
                                                    <line x1="9" y1="6" x2="20" y2="6"/>
                                                    <circle cx="5" cy="12" r="1"/>
                                                    <line x1="9" y1="12" x2="20" y2="12"/>
                                                </svg>
                                            </button>

                                        </div>
                                            
                                        <div class="col-6 text-end">
                                                <button class="btn btn-light botao-postar ">Postar</button>
                                        </div>
                                    </div>
                                </form>
                                    
                            </div>

                        </div>
                    </div>

                    <div id='quantidade-posts-novos' class='row d-none'>
                        <div class='d-flex justify-content-center align-items-center'>
                            <span id='span-quantidade'>
                            
                            </span>
                        </div>
                    </div>
                </div>

                <div id='posts'><!--Post em si !-->

                </div>

            </div>

        </div>
        

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>