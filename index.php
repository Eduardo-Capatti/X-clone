<?php
    session_start();

    print_r($_SESSION);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O que está acontecendo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>

        $(document).ready(()=>{

            let id = ''

            $('#criar').on('click', ()=>{
                $.post('criar_conta.php', (data) =>{
                    $('body').prepend(data)
                    id = '#criar-conta'
                })
            })

            $('#entrar').on('click', ()=>{
                $.post('entrar.php', (data) =>{
                    $('body').prepend(data)
                    id = '#entrar-conta'
                })
            })

            $('body').on('click', '#botao-fechar', ()=>{
                $(id).remove()
            })

            $('body').on('focus', '#celular-email',  ()=>{
                $('#label-alterna').addClass('onclick-label')
            })

            $('body').on('blur', '#celular-email',  ()=>{
                $('#label-alterna').removeClass('onclick-label')
            })

            $('body').on('focus', '#nome', ()=>{
                $('#label-nome').addClass('onclick-label')
            })

            $('body').on('blur', '#nome', ()=>{
                $('#label-nome').removeClass('onclick-label')
            })

            $('body').on('focus', '#senha', ()=>{
                $('#label-senha').addClass('onclick-label')
            })

            $('body').on('blur', '#senha', ()=>{
                $('#label-senha').removeClass('onclick-label')
            })

            

            $('body').on('click', '#alternar-input', ()=>{

                if ($('#label-alterna').html() == 'Celular'){

                    $('#celular-email').attr('name', 'email')
                    $('#label-alterna').html('E-mail')
                    $('#alternar-input').html('Use o celular')

                }else{

                    $('#celular-email').attr('name', 'celular')
                    $('#label-alterna').html('Celular')
                    $('#alternar-input').html('Use o E-mail')

                }
               
            })


            $('body').on('focus', '#mes', ()=>{
                $('#label-mes').addClass('onclick-label')
            })

            $('body').on('blur', '#mes', ()=>{
                $('#label-mes').removeClass('onclick-label')
            })

            $('body').on('focus', '#dia', ()=>{
                $('#label-dia').addClass('onclick-label')
            })

            $('body').on('blur', '#dia', ()=>{
                $('#label-dia').removeClass('onclick-label')
            })

            $('body').on('focus', '#ano', ()=>{
                $('#label-ano').addClass('onclick-label')
            })

            $('body').on('blur', '#ano', ()=>{
                $('#label-ano').removeClass('onclick-label')
            })

            $('body').on('click', '.botao-confirmar', (e)=>{
                e.preventDefault();
                let dados = $('form').serialize()
                
                if(id == '#criar-conta'){
                    $.ajax({
                    type:'post',
                    url: 'model.php?criar=true',
                    data: dados,
                    dataType: 'json',
                    success: dados=>{

                            if(dados.status == 'home.php'){
                                window.location.replace(dados.status)
                            }

                            if($('#conteudo-mensagem')[0] == null){
                                    $.post('mensagem.html', (data)=>{
                                        $('body').prepend(data)
                                        $('#mensagem p').html(dados.status)
                                        setTimeout(() => {
                                            $('#conteudo-mensagem').remove()
                                        }, 5000);
                                    })
                            }     
                    
                    },
                    error:  (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                })

                }else if(id == '#entrar-conta'){
                    $.ajax({
                    type:'post',
                    url: 'model.php?login=true',
                    data: dados,
                    dataType: 'json',
                    success: dados=>{

                            if(dados.status == 'home.php'){
                                window.location.replace(dados.status)
                            }
                        
                            if($('#conteudo-mensagem')[0] == null){
                                $.post('mensagem.html', (data)=>{
                                    $('body').prepend(data)
                                    $('#mensagem p').html(dados.status)
                                    setTimeout(() => {
                                        $('#conteudo-mensagem').remove()
                                    }, 5000);
                                })     
                        }
                    },
                    error:  (xhr, status, error) => {
                        console.error('Erro:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Detalhe:', error);}
                    })
                }
                
            })
        })

    </script>

<body>
    <div class="container">

        <div class="row align-items-center justify-content-center min-vh-100">

            <div class="col-6 text-center">
                <img src="x-logo.png" width="300px">
            </div>

            <div class="col-6 d-flex">
                
                <div class="mx-auto">
                <h1>Acontecendo agora</h1>

                    <div style="width: 350px;">
                        <h3>Inscreva-se hoje</h3>

                        <button class="btn" id="criar">Criar Conta</button>
                        <p>Ao se inscrever, você concorda com os <a href="#">Termos de Serviço</a> e a <a href="#">Política de Privacidade</a>, incluindo o <a href="#">Uso de Cookies</a>.</p>

                        <h5>Já tem uma conta?</h5>
                        <button class="btn" id="entrar">Entrar</button>
                    </div>
                </div>

            </div>

        </div>
    </div>
   
</body>
</html>