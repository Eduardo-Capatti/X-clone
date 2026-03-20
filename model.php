<?php

    class Perfil{
        private $id;
        private $nome;
        private $username;
        private $senha;
        private $foto;
        private $telefone;
        private $email;
        private $data_nasc;

        public function get($atributo){
            return $this->$atributo;
        }

        public function set($atributo, $valor){
            $this->$atributo = $valor;
        }
    }

    class Posts{
        private $id;
        private $idPerfil;
        private $texto;
        private $midia;

        public function get($atributo){
            return $this->$atributo;
        }

        public function set($atributo, $valor){
            $this->$atributo = $valor;
        }
    }

    class Comentarios{
        private $id;
        private $idPost;
        private $idPerfil;
        private $comentario;

        public function get($atributo){
            return $this->$atributo;
        }

        public function set($atributo, $valor){
            $this->$atributo = $valor;
        }
    }

    class Conexao{
        private $dbname = 'RedeSocial';
        private $host = 'localhost';
        private $user = 'root';
        private $pass = '';

        public function conectar(){
            try{
                $conexao = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname",
                    "$this->user",
                    "$this->pass"
                );

                $conexao->exec('set charset utf8mb4');

                return $conexao;

            }catch(PDOException $e){
                echo 'um erro aconteceu' . $e;
            }
            
        }
    }


    class Bd{
        private $perfil;
        private $post;
        private $comentario;
        private $conexao;

        public function __construct(Perfil $perfil, Posts $post, Comentarios $comentario, Conexao $conexao){
            $this->perfil = $perfil;
            $this->post = $post;
            $this->comentario = $comentario;
            $this->conexao = $conexao->conectar();
        }


        public function listar(){
            $query = 'select 
                        P.id, P.idPerfil, P.midia, P.texto, PE.nome, PE.foto , count(C.idPost) as curtidas, count(CO.idPost) as comentarios
                      from 
                        posts P inner JOIN perfil PE 
                      on
                        (P.idPerfil = PE.id)
                       	left join Curtidas C 
                      on
                      	(C.idPost = P.id)
                        left join Comentarios CO
                      on
                      	(CO.idPost = P.id)
                      group by P.id, P.idPerfil, P.midia, P.texto, PE.nome, PE.foto
                      order by P.id DESC ';

            $stmt = $this->conexao->prepare($query);

            $stmt->execute();
            $html = renderizarPost($stmt->fetchAll(PDO::FETCH_OBJ), $this->buscar_curtidas_itensSalvos('Curtidas'), $this->buscar_curtidas_itensSalvos('ItensSalvos'), $this->buscar_seguindo());

            $this->max_id();

            echo json_encode(['dados'=> $html]);
        }

        public function listar_seguindo(){
            $query = "select 
                        P.id, P.idPerfil, P.midia, P.texto, PE.nome, PE.foto , count(C.idPost) as curtidas, count(CO.idPost) as comentarios
                      from 
                        posts P inner JOIN perfil PE 
                      on
                        (P.idPerfil = PE.id)
                       	left join Curtidas C 
                      on
                      	(C.idPost = P.id)
                        left join Comentarios CO
                      on
                      	(CO.idPost = P.id)
                      	inner join Seguidores S
                      on
                      	(PE.id = S.user)
                      where 
                      	:idPerfil = S.seguidor
                      group by P.id, P.idPerfil, P.midia, P.texto, PE.nome, PE.foto
                      order by P.id DESC";

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue('idPerfil', $this->perfil->get('id'));
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            $html = empty($dados) ? 0 : renderizarPost($dados, $this->buscar_curtidas_itensSalvos('Curtidas'), $this->buscar_curtidas_itensSalvos('ItensSalvos'), $this->buscar_seguindo());
            echo json_encode(['dados'=> $html]);
        }

        public function listar_perfil(){
            $query = "select 
                        P.id, P.idPerfil, P.midia, P.texto, PE.nome, PE.foto , count(C.idPost) as curtidas, count(CO.idPost) as comentarios
                      from 
                        posts P inner JOIN perfil PE 
                      on
                        (P.idPerfil = PE.id)
                       	left join Curtidas C 
                      on
                      	(C.idPost = P.id)
                        left join Comentarios CO
                      on
                      	(CO.idPost = P.id)
                      where 
                      	:idPerfil = P.idPerfil
                      group by P.id, P.idPerfil, P.midia, P.texto, PE.nome, PE.foto
                      order by P.id DESC";

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue('idPerfil', $this->perfil->get('id'));
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            $html = empty($dados) ? 0 : renderizarPost($dados, $this->buscar_curtidas_itensSalvos('Curtidas'), $this->buscar_curtidas_itensSalvos('ItensSalvos'), $this->buscar_seguindo());
            echo json_encode(['dados'=> $html]);
        }

        public function buscar_curtidas_itensSalvos(String $tabela){
            $query = "select P.id from Posts P inner join $tabela $tabela[0] on P.id = $tabela[0].idPost where $tabela[0].idPerfil = :idPerfil";
            $resultado = [];
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue('idPerfil',$_SESSION['id']);
            
            $stmt->execute();

            $resposta = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach ($resposta as $valor){
                $resultado[] = $valor->id;
            }

            return $resultado;
        }

        public function buscar(){
            $query = 'select 
                        P.id, P.idPerfil, P.midia, P.texto, PE.nome, PE.foto , count(C.idPost) as curtidas, count(CO.idPost) as comentarios
                      from 
                        posts P inner JOIN perfil PE 
                      on
                        (P.idPerfil = PE.id)
                       	left join Curtidas C 
                      on
                      	(C.idPost = P.id)
                        left join Comentarios CO
                      on
                      	(CO.idPost = P.id)
                      where P.texto like (:texto)
                      group by P.id, P.idPerfil, P.midia, P.texto, PE.nome, PE.foto
                      order by P.id DESC' ;

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue('texto','%'.$_POST['buscar'].'%');
            $stmt->execute();

            $html = renderizarPost($stmt->fetchAll(PDO::FETCH_OBJ), $this->buscar_curtidas_itensSalvos('Curtidas'), $this->buscar_curtidas_itensSalvos('ItensSalvos'), $this->buscar_seguindo());
            echo json_encode(['dados'=> $html]);
        }

        public function buscar_seguindo(){
            $query = "select 
                        S.user
                      from
                        Seguidores S
                      where :idPerfil = S.seguidor";
            $resultado = [];

            $stmt = $this->conexao->prepare($query);

            $stmt->bindValue('idPerfil',$_SESSION['id']);
            
            $stmt->execute();

            $resposta = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach ($resposta as $valor){
                $resultado[] = $valor->user;
            }

            return $resultado;
        }

        public function criar_conta(){
            if ($this->perfil->get('telefone') != null){
                $input_value = 'telefone';
            }else{
                $input_value = 'email';
            }

            if ($this->verificar_conta('criar', $input_value)){
                $query = "insert into Perfil(nome, username, senha, $input_value, data_nascimento)  
                      values (:nome, :username, :senha, :input_value, :data_nascimento)";
            
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue('nome', $this->perfil->get('nome'));
                $stmt->bindValue('username', $this->perfil->get('nome'));
                $stmt->bindValue('senha', $this->perfil->get('senha'));
                $stmt->bindValue('input_value', $this->perfil->get($input_value));
                $stmt->bindValue('data_nascimento', $this->perfil->get('data_nascimento'));

                $stmt->execute();
                $this->entrar_conta($this->perfil->get('nome'));
                

            }else{
                echo json_encode(['status' => 'usuário e/ou email já existente(s)']);
            }     
        }

        public function entrar_conta($valor){
            $id_salvo = $this->verificar_conta('login', $valor);

            if($id_salvo != 0){
                session_start();
                $_SESSION['id'] = $id_salvo;
                echo json_encode(['status' => 'home.php']);
            }else{
                echo json_encode(['status' => 'usuário não encontrado']);
            }
        
        }

        private function verificar_conta(String $funcao, String $input_value){
            if ($funcao == 'criar'){
                $query = "select id from Perfil where binary nome = :nome or binary $input_value = :input_value limit 1";
                $stmt_verifica = $this->conexao->prepare($query);
                $stmt_verifica->bindValue('nome', $this->perfil->get('nome'));
                $stmt_verifica->bindValue('input_value', $this->perfil->get($input_value));

                $stmt_verifica->execute();

                if(count($stmt_verifica->fetchAll(PDO::FETCH_OBJ)) == 0){
                    return true;
                }else{
                    return false;
                }
            }else{
                $query = "select id from perfil where binary :valor in (telefone, email, nome) and binary senha = :senha";
                $stmt_verifica = $this->conexao->prepare($query);
                $stmt_verifica->bindValue('valor', $input_value);
                $stmt_verifica->bindValue('senha', $this->perfil->get('senha'));

                $stmt_verifica->execute();

                $id_salvo = $stmt_verifica->fetchAll(PDO::FETCH_OBJ)[0]->id ?? 0;
                return $id_salvo;
            }
            
        }

        public function buscar_info($id){
            $query = "select nome, username, foto from Perfil where id = $id";
            $stmt = $this->conexao->prepare($query);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);

        }

        public function postar(){
            $query = "insert into posts(idPerfil, texto, midia) 
                      values (:id, :mensagem, :midia)";
            
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue('id', $this->post->get('idPerfil'));
            $stmt->bindValue('mensagem', $this->post->get('texto'));
            $stmt->bindValue('midia', $this->post->get('midia'));

            $stmt->execute();

            $this->buscar_post_pos_postar();
        }

        public function buscar_post_pos_postar(){
            $query = 'select max(id) as id from posts where idPerfil = :id';

            $stmt = $this->conexao->prepare($query);

            $stmt->bindValue('id', $this->post->get('idPerfil'));
            $stmt->execute();
            $id = $stmt->fetchAll(PDO::FETCH_OBJ)[0]->id;

            array_push($_SESSION['ids-post'], $id);

            $query = "select 
                        P.id, P.idPerfil, P.midia, P.texto, PE.nome, PE.foto, count(C.idPost) as curtidas, count(CO.idPost) as comentarios
                      from 
                        posts P inner JOIN perfil PE 
                        left join Curtidas C on P.id = C.idPost
                        left join Comentarios CO on P.id = CO.idPost
                      where 
                        :idPerfil = PE.id and P.id = $id";

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue('idPerfil', $this->post->get('idPerfil'));
            $stmt->execute();
        
            $html = renderizarPost($stmt->fetchAll(PDO::FETCH_OBJ), [],[], []);
            echo json_encode(['dados'=>$html]);
        }

        public function max_id(){
            $query = 'select max(id) as id from posts';

            $stmt = $this->conexao->prepare($query);

            $stmt->execute();

            $id = $stmt->fetchAll(PDO::FETCH_OBJ)[0]->id;

            $_SESSION['max-id'] = $id;
        }

        public function buscar_novos_posts(){
            $valores = empty($_SESSION['ids-post']) ? 0 : implode(',', $_SESSION['ids-post'] );
            $id_max = $_SESSION['max-id'];
            $query = "select 
                        P.id, P.idPerfil, P.midia, P.texto, PE.nome, PE.foto, count(C.idPost) as curtidas, count(CO.idPost) as comentarios
                      from 
                        posts P inner JOIN perfil PE on P.idPerfil = PE.id
                        left join Curtidas C on P.id = C.idPost
                        left join Comentarios CO on P.id = CO.idPost
                      where 
                        P.idPerfil = PE.id and P.id not in($valores) and P.id > $id_max
                      group by
                        P.id
                      order by P.id DESC";

            $stmt = $this->conexao->prepare($query);

            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_OBJ);
            $html = empty($dados)? 0 : renderizarPost($dados,$this->buscar_curtidas_itensSalvos('Curtidas'), $this->buscar_curtidas_itensSalvos('ItensSalvos'), $this->buscar_seguindo());

            echo json_encode(['dados'=>$html, 'quantidade' => count($dados), 'max' => $_SESSION['max-id']]);
        }

        public function buscar_novos_posts_seguindo(){
            $valores = empty($_SESSION['ids-post']) ? 0 : implode(',', $_SESSION['ids-post'] );
            $id_max = $_SESSION['max-id'];
            $query = "select 
                        P.id, P.idPerfil, P.midia, P.texto, PE.nome, PE.foto, count(C.idPost) as curtidas, count(CO.idPost) as comentarios
                      from 
                        posts P inner JOIN perfil PE on P.idPerfil = PE.id
                        left join Curtidas C on P.id = C.idPost
                        left join Comentarios CO on P.id = CO.idPost
                        left join Seguidores S on P.idPerfil = S.user
                      where 
                        P.idPerfil = PE.id and P.id not in($valores) and P.id > $id_max and :id = S.seguidor
                      group by
                        P.id
                      order by P.id DESC";

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue('id', $this->perfil->get('id'));

            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_OBJ);
            $html = empty($dados)? 0 : renderizarPost($dados,$this->buscar_curtidas_itensSalvos('Curtidas'), $this->buscar_curtidas_itensSalvos('ItensSalvos'), $this->buscar_seguindo());

            echo json_encode(['dados'=>$html, 'quantidade' => count($dados), 'max' => $_SESSION['max-id']]);
        }

        public function curtir_salvar(String $tabela){
            $query = "select idPost from $tabela where idPerfil = :idPerfil and idPost = :idPost";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue('idPerfil', $this->post->get('idPerfil'));
            $stmt->bindValue('idPost', $this->post->get('id'));
            $stmt->execute();

            $lista = $stmt->fetchAll(PDO::FETCH_OBJ);

            if (empty($lista)){
                $query = "insert into $tabela(idPost, idPerfil) values(:idPost, :idPerfil)";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue('idPost', $this->post->get('id'));
                $stmt->bindValue('idPerfil', $this->post->get('idPerfil'));

                $stmt->execute();

                echo json_encode(['status' => 'colocou']);
            }else{
                $query = "delete from $tabela where idPost = :idPost and idPerfil = :idPerfil";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue('idPost', $this->post->get('id'));
                $stmt->bindValue('idPerfil', $this->post->get('idPerfil'));

                $stmt->execute();

                echo json_encode(['status' => 'tirou']);
            }
            
        }

        public function comentar(){
            $query = "insert into Comentarios(idPerfil, idPost, comentario)
                      values(:idPerfil, :idPost, :comentario)";

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue('idPerfil', $this->comentario->get('idPerfil'));
            $stmt->bindValue('idPost', $this->comentario->get('idPost'));
            $stmt->bindValue('comentario', $this->comentario->get('comentario'));

            $stmt->execute();
        }

        public function seguir(){

            $query = "select
                        user, seguidor
                      from
                        Seguidores
                      where 
                        user = :idUser and seguidor = :idSeguidor";

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue('idUser', $this->post->get('idPerfil'));
            $stmt->bindValue('idSeguidor', $this->perfil->get('id'));
            $stmt->execute();

            $lista = $stmt->fetchAll(PDO::FETCH_OBJ);

            if(empty($lista)){
                $query = "insert into Seguidores(user, seguidor)
                           values(:idUser, :idSeguidor)";

                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue('idUser', $this->post->get('idPerfil'));
                $stmt->bindValue('idSeguidor', $this->perfil->get('id'));

                $stmt->execute();

                echo json_encode(['status' => 'colocou']);
            }else{
                $query = "delete
                          from
                            Seguidores
                          where 
                            user = :idUser and seguidor = :idSeguidor";

                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue('idUser', $this->post->get('idPerfil'));
                $stmt->bindValue('idSeguidor', $this->perfil->get('id'));

                $stmt->execute();

                echo json_encode(['status' => 'tirou']);
            }
           
            
        }

        public function selecionar_itensSalvos(){
            $query = "select 
                        P.id, P.idPerfil, P.midia, P.texto, PE.nome, PE.foto, count(C.idPost) as curtidas, count(CO.idPost) as comentarios
                      from 
                        posts P inner JOIN perfil PE on P.idPerfil = PE.id
                        left join Curtidas C on P.id = C.idPost
                        left join Comentarios CO on P.id = CO.idPost
                        left join itenssalvos I on P.id = I.idPost
                      where 
                        I.idPerfil = :idPerfil
                      group by
                        P.id
                      order by
                        P.id DESC";
            
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue('idPerfil', $this->post->get('idPerfil'));
            $stmt->execute();

            $dados = $stmt->fetchAll(PDO::FETCH_OBJ);
            $html = empty($dados)? 0 : renderizarPost($dados,$this->buscar_curtidas_itensSalvos('Curtidas'), $this->buscar_curtidas_itensSalvos('ItensSalvos'), $this->buscar_seguindo());
            
            echo json_encode(['dados'=> $html]);

        }

    }

    include ('posts.php');

    $perfil = new Perfil();
    $post = new Posts();
    $comentario = new Comentarios();
    $conexao = new Conexao();

    $bd = new Bd($perfil, $post, $comentario, $conexao);

    if(isset($_GET['criar']) && $_GET['criar'] == 'true'){
        if($_POST['nome'] == null || $_POST['senha'] == null || $_POST['mes'] == 0 || $_POST['dia'] == 0 || $_POST['ano'] == 0 || ((isset($_POST['celular']) && $_POST['celular'] == null) && (isset($_POST['email']) && $_POST['email'] == null)) ){
            echo json_encode(['status' => 'preencha todas as informacoes']);
        }else{
            $perfil->set('nome', $_POST['nome']);
            $perfil->set('username', $_POST['nome']);
            $perfil->set('senha', $_POST['senha']);
            $perfil->set('data_nascimento', $_POST['ano'] . '-' . $_POST['mes'] . '-' . $_POST['dia']);
            if (isset($_POST['celular'])){
                $perfil->set('telefone', $_POST['celular']);
            }else{
                $perfil->set('email', $_POST['email']);
            }
            
            $bd->criar_conta();

        }
    }


    if(isset($_GET['login']) && $_GET['login'] == 'true'){
        if($_POST['nome'] == null || $_POST['senha'] == null){
            echo json_encode(['status' => 'preencha todas as informacoes']);
        }else{
            $perfil->set('senha', $_POST['senha']);
            $bd->entrar_conta($_POST['nome']);
        }
    }

    if(isset($_GET['postar']) && $_GET['postar'] == 'true'){
        session_start();
        
        //Jogando o arquivo em uma pasta
        if($_FILES['midia']['error'] === 0 ){
            $tmpName = $_FILES['midia']['tmp_name'];
            $baseName = basename($_FILES['midia']['name']);
            $novoNome = uniqid() . '-' . $baseName;
            $caminho = 'uploads/' . $_SESSION['id'] . '/' . $novoNome;

            move_uploaded_file($tmpName,$caminho);
        }

        $arquivo = isset($novoNome) ? $novoNome : null;

        $post->set('idPerfil', $_SESSION['id']);
        $post->set('midia', $arquivo);
        $post->set('texto', $_POST['mensagem']);

        
        $bd->postar();
    }

    if(isset($_GET['listar']) && $_GET['listar'] == 'true'){
        session_start();
        $bd->listar();
    }

    if(isset($_GET['buscar_novos_posts']) && $_GET['buscar_novos_posts'] == 'true'){
        session_start();
        $bd->buscar_novos_posts();
    }

    if(isset($_GET['retornar_id_max']) && $_GET['retornar_id_max'] == 'true'){
        session_start();
        $bd->max_id();
    }

    if(isset($_GET['curtir']) && $_GET['curtir'] == 'true'){
        session_start();
        $post->set('idPerfil', ($_SESSION['id']));
        $post->set('id', ($_POST['idPost']));
        $bd->curtir_salvar('Curtidas');
    }

    if(isset($_GET['salvar']) && $_GET['salvar'] == 'true'){
        session_start();
        $post->set('idPerfil', ($_SESSION['id']));
        $post->set('id', ($_POST['idPost']));
        $bd->curtir_salvar('ItensSalvos');
    }

    if(isset($_GET['postar-comentario']) && $_GET['postar-comentario'] == 'true'){
        session_start();
        $comentario->set('comentario', $_POST['responder']);
        $comentario->set('idPost', $_POST['idPost']);
        $comentario->set('idPerfil', $_SESSION['id']);
        $bd->comentar();
    }

    if(isset($_GET['buscar-itensSalvos']) && $_GET['buscar-itensSalvos'] == 'true'){
        session_start();
        $post->set('idPerfil', $_SESSION['id']);
        $bd->selecionar_itensSalvos();
    }

    if(isset($_GET['seguir']) && $_GET['seguir'] == 'true'){
        session_start();
        $post->set('idPerfil', $_POST['idPerfil']);
        $perfil->set('id', $_SESSION['id']);
        $bd->seguir();
    }

    if(isset($_GET['listar-seguindo']) && $_GET['listar-seguindo'] == 'true'){
        session_start();
        $perfil->set('id', $_SESSION['id']);
        $bd->listar_seguindo();
    }

    if(isset($_GET['buscar']) && $_GET['buscar'] == 'true'){
        session_start();
        $bd->buscar();
    }

    if(isset($_GET['buscar_novos_posts_seguindo']) && $_GET['buscar_novos_posts_seguindo'] == 'true'){
        session_start();
        $perfil->set('id', $_SESSION['id']);
        $bd->buscar_novos_posts_seguindo();
    }

    if(isset($_GET['listar-perfil']) && $_GET['listar-perfil'] == 'true'){
        session_start();
        $perfil->set('id', $_SESSION['id']);
        $bd->listar_perfil();
    }

?>


