<div id="div-responder">
    <div id="conteudo-div-responder">
                        
        <div class="row"  style="width:500px;">
            <div class="col-1">
                <img src=<?=$_POST['foto']?> width="40px">
            </div>

            <div class="col-11">
                <form id="form-responder">
                    <textarea name="responder" id="responder" placeholder="Postar sua resposta"></textarea>
                    
                    <div class="col-12 text-end">
                        <button class="btn btn-light botao-postar-comentario" onclick="postar_comentario()">Postar</button>
                    </div>
                </form>              
            </div>

        </div>

    </div>
</div>