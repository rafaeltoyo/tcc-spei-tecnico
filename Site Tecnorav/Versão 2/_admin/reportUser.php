<!DOCTYPE html>
<?php
include('config.php');
include('function.php');
include('configAdmin.php');
?>
<script>
    function ClearAllFields (){
    document.getElementById("name").value = "";
    document.getElementById("loginfield").value = "";
    document.getElementById("idDateFilter1").value = "";
    document.getElementById("idDateFilter2").value = "";
    document.getElementById("orderUserReport").value = "";
    }
</script>

<header id="cabecalho">
</header>
<form action="#" method="post" name="userReport">

    <form action="#" method="POST">
        <aside id="lateral">
            <fieldset id="busca">
                <div id="Filter">
                    <table id="relatorio">
                        <tr>
                            <td colspan="2">User report:</td>
                        </tr>
                        <tr>
                            <td>
                                Name: &nbsp;
                            </td>
                            <td>
                                <input type="text" name="name" id="name" value="<?php echo @$_POST['name']?>" placeholder="Write the name here">&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Login: &nbsp;
                            </td>
                            <td>
                                <input type="text" name="login" id="loginfield" value="<?php echo @$_POST['login']?>" placeholder="Write the login here">&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td> Date Filter: </td>
                            <td>
                                Born from: <input type="date" id="idDateFilter1" name="nDateFilter1" value="<?php echo @$_POST['nDateFilter1']; ?>" />
                                To: <input type="date" id="idDateFilter2" name="nDateFilter2" value="<?php echo @$_POST['nDateFilter2']; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Order by: &nbsp;
                            </td>
                            <td>
                                <select name="orderUserReport" id="orderUserReport" >
                                    <option value="">Select here</option>
                                    <option value="login" <?php echo(@$_POST['orderUserReport']=='login' ? 'selected' : "")?>>Login</option>
                                    <option value="name" <?php echo(@$_POST['orderUserReport']=='name' ? 'selected' : "")?>>Name</option>
                                    <option value="gender" <?php echo(@$_POST['orderUserReport']=='gender' ? 'selected' : "")?>>Gender</option>
                                    <!--<option value="birth" <?//php echo(@$_POST['orderUserReport']=='birth' ? 'selected' : "")?>>Birth</option>-->
                                </select>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                &nbsp;<input type="submit" id="" name="button" value="Search" />
                            </td>
                            <td>
                                &nbsp;<input type="button" id="clear" name="botao" value="Clear" onclick="ClearAllFields()"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php if (@$_REQUEST['button'] == "Search") { ?>
                    <div>
                        <table id="relatorio">
                            <tr>
                                <td>Name:</td>
                                <td>Login:</td>
                                <td>Gender:</td>
                                <td>Birth:</td>
                                <td>Email:</td>
                            </tr>
                            <?php
                            @$login = $_POST['login'];
                            @$name = $_POST['name'];
                            @$gender = $_POST['gender'];
                            @$order = $_POST['orderUserReport'];

                            $query = "SELECT *
				  FROM user
				  WHERE name IS NOT NULL";
                            $query .= ($name ? " AND name LIKE '%$name%' " : "");
                            $query .= ($login ? " AND login LIKE '%$login%' " : "");

                            @$data_inicio = $_POST['nDateFilter1']." 00:00:00";
                            @$data_final = $_POST['nDateFilter2']." 23:59:59";
                            if ( validateDate($data_inicio) && validateDate($data_final) ) {
                                if( strtotime( $_POST['nDateFilter1'] ) >= strtotime( $_POST['nDateFilter2'] ) ) {
                                    $data_inicio = $_POST['nDateFilter1']." 00:00:00";
                                    $data_final = $_POST['nDateFilter1']." 23:59:59";
                                } else {
                                    $data_inicio = $_POST['nDateFilter1']." 00:00:00";
                                    $data_final = $_POST['nDateFilter2']." 23:59:59";
                                }
                                $query .= " AND birth between '$data_inicio' and '$data_final'";
                            }

                            $query .= ($order==!NULL ? " ORDER BY $order " : "");

                            $result = mysql_query($query) or die (mysql_error());
                            while($coluna = mysql_fetch_assoc($result)){
                                ?>
                                <tr id="user_body">
                                    <td id="user_name"><?php echo $coluna['name']; ?></td>
                                    <td id="user_login"><?php echo $coluna['login']; ?></td>
                                    <td id="user_gender"><?php echo $coluna['gender']; ?></td>
                                    <td id="user_birth"><?php echo $coluna['birth']; ?></td>
                                    <td id="user_email"><?php echo $coluna['email']; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            </ul>
                        </table>
                    </div>
                <?php } ?>
    </form>

    </aside>
</form>

</body>
</html>