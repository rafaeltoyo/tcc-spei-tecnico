<?php
@session_start();

/* Criptografia de senha */
function validateDate($date,$format = 'Y-m-d H:i:s') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function encrypt($password){

    $first = md5($password);

    $second = hash('sha512',$first);

    return $second;

}
function editUnote ($id_note,$operation) {
    /*
     * $operation - Legenda:
     * change - Adicionar ou Excluir a nota de unotes
     *
    */

    if ($operation == "delete") {
        $query = "DELETE FROM notes WHERE id_notes = '$id_note' AND ( creator = '{$_SESSION['login']}' OR id_schedule IN ( SELECT schedule.id FROM schedule INNER JOIN uschedule ON schedule.id = uschedule.id_schedule WHERE ( uschedule.login = '{$_SESSION['login']}' AND uschedule.rank = 2 ) OR schedule.administrator = '{$_SESSION['login']}' ) )";
        $result = mysql_query($query) or die (mysql_error());
        return true;
    }

    /*
     * VEREFICADOR 1:
     * Procura a nota com o ID passado pela função, busca a agenda que essa nota está e verefica se o usuário da sessão está nela.
     * Retorna info da nota se ela pertence a uma agenda sua.
     * ( Verefica se não se trata de uma nota inválida para você ).
    */
    $query = "SELECT * FROM notes WHERE id_notes = '$id_note' AND id_schedule IN ( SELECT schedule.id FROM schedule INNER JOIN uschedule ON schedule.id = uschedule.id_schedule WHERE uschedule.login = '{$_SESSION['login']}' )";
    $result = mysql_query($query) or die (mysql_error());
    $get_note_info = mysql_fetch_assoc($result);
    if ($get_note_info == null) {
        $_SESSION['mensagem'] = "Anotação não pertence as suas agendas!";
        return false;
    } else {
        $alert_notes_time = $get_note_info['date'];
    }

    /* VEREFICADOR 2: Verefica se a anotação da Url já está salva nas notas do usuário. */
    $note_exist = false;
    $query = "SELECT * FROM unotes WHERE id_notes = '$id_note' AND login = '{$_SESSION['login']}'";
    $result = mysql_query($query) or die (mysql_error());
    while ($coluna = mysql_fetch_array($result)) {
        $note_exist = true;
    }

    /* Pegar o tempo de alerta padrão do usuário */
    $query = "SELECT * FROM user WHERE login = '{$_SESSION['login']}'";
    $result = mysql_query($query) or die (mysql_error());
    while ($coluna = mysql_fetch_array($result)) { $alert_user_time=$coluna['alert_time']; }

    /* Gera um horário de alerta baseado na data da nota e o tempo padrão do usuário */
    $alert_new_time = date("Y-m-d H:i:s", strtotime($alert_notes_time) - $alert_user_time*60);

    /* Se encontrado algum dado: */
    if (!$note_exist && $operation == "change") {
        $query = "INSERT INTO unotes(id_notes,login,alarm) VALUES('$id_note','{$_SESSION['login']}','$alert_new_time')";
        $result = mysql_query($query) or die (mysql_error());
        return true;
    } else if ($note_exist && $operation == "change") {
        $query = "DELETE FROM unotes WHERE id_notes = '$id_note' AND login = '{$_SESSION['login']}'";
        $result = mysql_query($query) or die (mysql_error());
        return true;
    } else {
        $_SESSION['mensagem'] = "Error!";
    }

    return false;
}

/* Vereficações de campos */
function verefFields ($login,$password,$password2,$email,$sexo,$nome,$nascimento,$fuso) {
    unset ($_SESSION['mensagem']);

    if (verefLogin($login) != "")
        $_SESSION['mensagem'] = verefLogin($login);

    if (verefPass($password) != "")
        $_SESSION['mensagem'] = verefPass($password);

    if ($password2 != $password)
        $_SESSION['mensagem'] = "Senhas incompatíveis";

    if (!verefEmail($email))
        $_SESSION['mensagem'] = "E-mail inválido!";

    if ($sexo == "")
        $_SESSION['mensagem'] = "Selecione um sexo!";

    if ($nome == "")
        $_SESSION['mensagem'] = "Nome está em branco!";

    if ($nascimento == "")
        $_SESSION['mensagem'] = "Data de nascimento inválida!";

    if ($fuso == "")
        $_SESSION['mensagem'] = "Escolha um fuso horário!";

    if (isset($_SESSION['mensagem']))
        return false;

    return true;
}

function verefSchedule ($nome,$privacidade,$tipo){
    if (verefNome($nome) != "")
        $_SESSION['mensagem'] = verefNome($nome);

    if ($privacidade != "")
        $_SESSION['mensagem'] = "Escolha uma privacidade";

    if ($tipo != "")
        $_SESSION['mensagem'] = "Escolha um tipo!";
}

function verefNome ($nome) {
    if ( strlen($nome) < 8 || strlen($nome) > 20 )
        return "O nome deve conter entre 6 a 30 caracteres!";
    if( !ctype_alnum( $nome ) )
        return "Nome inválido, use apenas Letras e Números (ç e acentos não)!";
    return "";
}

function verefLogin ($login) {
    if ( strlen($login) < 8 || strlen($login) > 20 )
        return "Login deve conter entre 6 a 30 caracteres!";
    if( !ctype_alnum( $login ) )
        return "Login inválido, use apenas Letras e Números (ç e acentos não)!";
    return "";
}

function verefPass ($password) {
    if ( strlen($password) < 8 || strlen($password) > 20 )
        return "Senha deve conter entre 8 a 20 caracteres!";
    return "";
}

function verefEmail($endereço) {
    $Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
    if(preg_match($Syntaxe,$endereço))
        return true;
    else
        return false;
}

function verefNull ($field) {
    if ($field == "")
        return false;
    else
        return true;
}
?>