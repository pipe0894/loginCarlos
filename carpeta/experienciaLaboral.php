<?php 
	
	require 'admin/config.php';
	require 'funciones.php';
	session_start();
	if (!isset($_SESSION['usuario'])) {
		header('Location: login.php');
	}
	$conexion = conexion($bd_config);
	$errores = '';
	$perfilUsuario = '';
	$trabajoVmca = '';
	$trabajaVmca = '';
	$traslado = '';
	$viajar = '';
	$preferencia = '';
	$idCurriculum = '';

	$empresa = '';
	$inicio = '';
	$fin = '';
	$industria = '';
	$rol = '';
	$roles = todosRolesFiltrados();
	$industrias = todasIndustriasFiltrada();
	$numRegistros = '';
	$numRegistrosIzquierda = '';
	$numRegistrosDerecha = '';
	$contadorRegistros = 1;
	$auxCont = true;

	$infoLAboral = getCurriculum($_SESSION['usuario']);

	while ($fila = mysql_fetch_array($infoLAboral)) {
		$idCurriculum = $fila['id'];
		$perfilUsuario = $fila['perfil'];
		$trabajoVmca = $fila['trabajoVmca'];
		$trabajaVmca = $fila['trabajaVmca'];
		$traslado = $fila['dispuestoViajar'];
		$viajar = $fila['dispuestoTraslado'];
		$preferencia = $fila['preferenciaLaboral'];
	}
	$experienciaLaboral = getExperiencia($idCurriculum);
	$auxTemp = numRegistrosExperiencia($idCurriculum);
	while ($fila2 = mysql_fetch_array($auxTemp)) {
		$numRegistros = $fila2[0];
	}
	$numRegistrosIzquierda = ceil($numRegistros/2);
	$numRegistrosDerecha = $numRegistros - $numRegistrosIzquierda;
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(!empty($_POST['borrarRespo'])){
			borrarResponsabilidad($_POST['borrarRespo']);
		}elseif(!empty($_POST['guardarRespo'])){
			$aux = 'respon-' . $_POST['guardarRespo'];
			actualizarResponsabilidad($_POST['guardarRespo'],$_POST[$aux]);
		}elseif(!empty($_POST['saveResponsabilidad'])){
			nuevaResponsabilidad($_POST['saveResponsabilidad'],$_POST['nuevaRespons']);
		}elseif(!empty($_POST['saveExperiencia'])){
			if (empty($_POST['newProyecto'])) {
				$_POST['newProyecto'] = 'NO';
			}
			$puedeEnviar = true;
			if (empty($_POST['newEmpresa'])) {
				$puedeEnviar = false;
				$errores = $errores . '<li>Debe especificar el nombre de la empresa en que trabajó</li>';
			}
			if (empty($_POST['newFechaInicio'])) {
				$puedeEnviar = false;
				$errores = $errores . '<li>Debe especificar la fecha de inicio</li>';	
			}
			if (empty($_POST['newIndustria'])) {
				$puedeEnviar = false;
				$errores = $errores . '<li>Debe especificar la industria</li>';
			}
			if (empty($_POST['newRol'])) {
				$puedeEnviar = false;
				$errores = $errores . '<li>Debe especificar el rol</li>';
			}
			if($puedeEnviar){
				nuevaExperiencia($idCurriculum,$_POST['newEmpresa'],$_POST['newFechaInicio'],$_POST['newFechaFin'],$_POST['newIndustria'],$_POST['newRol'],$_POST['newProyecto']);
			}
			header('Location: experienciaLaboral.php');
		}elseif (!empty($_POST['borrarEmpresa'])) {
			borrarExperiencia($_POST['borrarEmpresa']);
			header('Location: experienciaLaboral.php');
		}elseif(!empty($_POST['guardarCurriculum'])){
			if (!empty($_POST['trabajo'])) {
				$trabajoVmca = 1;
			}else{
				$trabajoVmca = 0;
			}
			if (!empty($_POST['trabaja'])) {
				$trabajaVmca = 1;
			}else{
				$trabajaVmca = 0;
			}
			if (!empty($_POST['traslado'])) {
				$traslado = 1;
			}else{
				$traslado = 0;
			}
			if (!empty($_POST['viajar'])) {
				$viajar = 1;
			}else{
				$viajar = 0;
			}
			$perfil = $_POST['newPerfil'];
			actualizarCurriculum($trabajoVmca,$trabajaVmca,$traslado,$viajar,$perfil,$_SESSION['usuario']);
			header('Location: experienciaLaboral.php');
		}
	}

	require 'views/experienciaLaboral.view.php';
?>