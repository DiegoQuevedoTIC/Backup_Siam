@php
    // Asegúrate de que 'fecha_nacimiento' sea un string en formato adecuado
    $fechaNacimiento = \Carbon\Carbon::createFromFormat('Y-m-d', $asociado['fecha_nacimiento']);
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Solicitud de Crédito</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 8px;
            display: flex;
            justify-content: space-between;
        }

        .table-stripe {
            width: 60px;
            min-width: 60px;
            background-color: #009959;
            flex-shrink: 0;
            height: 60vh;
        }

        .main-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #000;
            border-radius: 20px;
            overflow: hidden;
        }

        .header {
            background-color: #009959;
            color: white;
            font-weight: bold;
            padding: 5px;
            text-align: center;
        }

        .header-gray {
            background-color: #bdc0be;
            color: white;
            font-weight: bold;
            padding: 5px;
            text-align: center;
        }


        td {
            border: 1px solid #000;
        }

        .no-border {
            border: none;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="date"] {
            width: 95%;
            padding: 2px;
            border: none;
            background-color: transparent;
            font-size: 10px !important;
            font-weight: bold !important;
        }

        input[type="checkbox"] {
            margin: 0 5px;
            background: transparent;
        }

        .section-title {
            background-color: #009959;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 5px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            float: right;
        }

        .description {
            font-size: 8px;
            padding: 10px;
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            margin-top: 20px;
        }

        .footer-title {
            color: #009959;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .footer-contact {
            margin-top: 5px;
        }

        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .signature-box {
            width: 32%;
            text-align: center;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }

        .second-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            border-radius: 20px;
            overflow: hidden;

            th,
            tr,
            td {
                border: none;
                padding: 10px;
            }
        }

        .text-bold {
            font-size: 30px !important;
            font-weight: bold !important;
        }
    </style>
</head>

<body>
    <div class="page-container">
        <!-- header and logo section -->
        <img src="{{ public_path('images/logo_bg.png') }}" alt="Logo" style="width: 100px;">
        <!-- end of header and logo section -->

        <!-- Descripcion -->
        <span class="title">
            {{ now()->format('Y-m-d') }}
            <br>
            SOLICITUD DE CRÉDITO
        </span>

        <div class="main-content">
            <!-- Crédito Solicitado Section -->
            <table class="main-table">
                <tr>
                    <td colspan="4" class="header">CRÉDITO SOLICITADO</td>
                </tr>
                <tr>
                    <td width="25%">MONTO $<input type="number"
                            value="{{ number_format($credito['vlr_solicitud'], 2) }}"></td>
                    <td width="25%">EN LETRAS<input type="text" value=""></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>PLAZO EN MESES<br>(CUOTAS)<input type="number" value="{{ $credito['nro_cuotas_max'] }}"></td>
                    <td>VALOR CUOTA MENSUAL<input type="number" value="{{ number_format($credito['vlr_planes'], 2) }}"></td>
                    <td colspan="2">
                        LÍNEA DE CRÉDITO<br>
                        VIVIENDA <input type="checkbox">
                        LIBRE INVERSIÓN <input type="checkbox">
                        VEHÍCULO <input type="checkbox"><br>
                        OTRO <input type="checkbox"> CUÁL <input type="text" style="width: 100px;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        FAVOR ABONAR EL DESEMBOLSO DE ESTE CRÉDITO A MI CUENTA<br>
                        BANCO <input type="text" style="width: 200px;"><br>
                        AHORROS <input type="checkbox">
                        CORRIENTE <input type="checkbox">
                        No. <input type="text" style="width: 150px;">
                    </td>
                    <td colspan="2">
                        O GIRAR CHEQUE A<br>
                        <input type="text" style="width: 100%;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        OFICINA RECEPTORA<br>
                        <input type="text" style="width: 100%;">
                    </td>
                    <td>
                        CIUDAD<br>
                        <input type="text" style="width: 100%;">
                    </td>
                    <td>
                        FECHA<br>
                        <div style="display: flex; gap: 5px;">
                            <input type="number" placeholder="DD" style="width: 30px;" value="{{ now()->format('d') }}"
                                min="1" max="31">
                            <input type="number" placeholder="MM" style="width: 30px;" value="{{ now()->format('m') }}"
                                min="1" max="12">
                            <input type="number" placeholder="AAAA" style="width: 50px;"
                                value="{{ now()->format('Y') }}">
                        </div>
                    </td>
                </tr>
            </table>

            <br>

            <!-- Información Personal Section -->
            <table class="main-table">
                <tr>
                    <td colspan="4" class="header">INFORMACIÓN PERSONAL - DEUDOR SOLIDARIO 1</td>
                </tr>
                <tr>
                    <td width="33%">PRIMER APELLIDO<br><input type="text"
                            value="{{ $tercero['primer_apellido'] ?? '' }}"></td>
                    <td width="33%">SEGUNDO APELLIDO<br><input type="text"
                            value="{{ $tercero['segundo_apellido'] ?? '' }}"></td>
                    <td colspan="2">NOMBRES<br><input type="text" value="{{ $tercero['nombres'] ?? '' }}"></td>
                </tr>
                <tr>
                    <td colspan="2">EMPRESA<br><input type="text" value="{{ $asociado['empresa'] ?? '' }}"></td>
                    <td>CARGO<br><input type="text"></td>
                    <td>ANTIGÜEDAD<br><input type="text"></td>
                </tr>
                <tr>
                    <td>
                        FECHA DE NACIMIENTO<br>
                        <div style="display: flex;">
                            <input type="text" value="{{ $tercero['fecha_nacimiento'] }}">
                        </div>
                    </td>
                    <td>CÉDULA<br><input type="text" value="{{ $tercero['tercero_id'] ?? ''}}"></td>
                    <td>EXPEDIDA EN<br><input type="text"></td>
                    <td>
                        EMPLEADO ACTIVO <input type="checkbox">
                        PENSIONADO <input type="checkbox">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">DIRECCIÓN RESIDENCIA<br><input type="text" {{ $tercero['direccion'] }}>
                    </td>
                    <td>TELÉFONO<br><input type="text" value="{{ $tercero['telefono'] ?? '' }}"></td>
                    <td>CELULAR<br><input type="text" value="{{ $tercero['celular'] ?? '' }}"></td>
                </tr>
                <tr>
                    <td>CIUDAD<br><input type="text"></td>
                    <td>DEPARTAMENTO<br><input type="text"></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="2">DIRECCIÓN LABORAL<br><input type="text"
                            value="{{ $asociado['direccion_empresa'] ?? '' }}"></td>
                    <td>TELÉFONO<br><input type="text" value="{{ $asociado['telefono_empresa'] }}"></td>
                    <td></td>
                </tr>
                <tr>
                    <td>CIUDAD<br><input type="text"></td>
                    <td>DEPARTAMENTO<br><input type="text"></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="3">CORREO ELECTRÓNICO (E-MAIL)<br><input type="email"
                            value="{{ $tercero['email'] ?? '' }}"></td>
                    <td>
                        ENVÍO CORRESPONDENCIA<br>
                        OFICINA <input type="checkbox">
                        RESIDENCIA <input type="checkbox">
                    </td>
                </tr>
            </table>

            <br>

            <!-- Información Financiera Section -->
            <table class="main-table">
                <tr>
                    <td colspan="4" class="header">INFORMACIÓN FINANCIERA - DEUDOR SOLIDARIO 1</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; font-weight: bold;">INGRESOS MENSUALES</td>
                    <td colspan="2" style="text-align: center; font-weight: bold;">EGRESOS MENSUALES</td>
                </tr>
                <tr>
                    <td>SALARIO y/o PENSIÓN</td>
                    <td>$ <input type="text" value="{{ number_format($finanzas['salario'], 2) }}"></td>
                    <td>ARRIENDO/CUOTA VIVIENDA</td>
                    <td>$ <input type="text" value="{{ number_format($finanzas['otros_gastos'], 2) }}"></td>
                </tr>
                <tr>
                    <td>OTROS INGRESOS*</td>
                    <td>$ <input type="text" value="{{ number_format($finanzas['gastos_sostenimiento'], 2) }}"></td>
                    <td>GASTOS PERSONALES/FAMILIARES</td>
                    <td>$ <input type="text" value="0.00"></td>
                </tr>
                <tr>
                    <td>TOTAL INGRESOS</td>
                    <td>$ <input type="text" value="{{ number_format($finanzas['total_ingresos'] ?? 0, 2) }}"></td>
                    <td>TOTAL EGRESOS</td>
                    <td>$ <input type="text" value="0.00"></td>
                </tr>
                <tr>
                    <td colspan="4" class="description">* DESCRIPCIÓN OTROS INGRESOS</td>
                </tr>
                <tr>
                    <td colspan="2" class="header-gray">ACTIVOS</td>
                    <td colspan="2" class="header-gray">PASIVOS</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table width="100%" style="padding: 5px;">
                            <tr>
                                <td colspan="3">FINCA RAÍZ</td>
                            </tr>
                            <tr>
                                <td>TIPO <input type="text"></td>
                                <td>CIUDAD <input type="text"></td>
                                <td>VALOR COMERCIAL $ <input type="text" value="0.00"></td>
                            </tr>
                            <tr>
                                <td colspan="3">VEHÍCULO</td>
                            </tr>
                            <tr>
                                <td>MARCA <input type="text"></td>
                                <td>MODELO <input type="text"></td>
                                <td>PLACA <input type="text"></td>
                            </tr>
                            <tr>
                                <td colspan="3">VALOR COMERCIAL $ <input type="number"></td>
                            </tr>
                            <tr>
                                <td colspan="2">OTROS ACTIVOS</td>
                                <td>$ <input type="number"></td>
                            </tr>
                            <tr>
                                <td colspan="2">VALOR TOTAL ACTIVOS</td>
                                <td>$ <input type="number"></td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="2">
                        <table width="100%" style="padding: 5px;">
                            <tr>
                                <td>HIPOTECA</td>
                                <td colspan="2">$ <input type="number"></td>
                            </tr>
                            <tr>
                                <td>GASTOS FINANCIEROS (Créditos, T.C., etc.)</td>
                                <td colspan="2">$ <input type="number"></td>
                            </tr>
                            <tr>
                                <td>GASTOS DE SOSTENIMIENTO</td>
                                <td colspan="2">$ <input type="text" value="{{ number_format($finanzas['gastos_sostenimiento'], 2) }}"></td>
                            </tr>
                            <tr>
                                <td>OTROS PASIVOS</td>
                                <td colspan="2">$ <input type="text" value="0.00"></td>
                            </tr>
                            <tr>
                                <td>VALOR TOTAL PASIVOS</td>
                                <td colspan="2">$ <input type="text" value="0.00"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div class="footer">
                <div class="footer-title">
                    FONDO NACIONAL DE EMPLEADOS, TRABAJADORES Y PENSIONADOS DEL SECTOR POSTAL<br>
                    DE LAS COMUNICACIONES, ENTIDADES AFINES Y COMPLEMENTARIAS - FONDEP
                </div>
                <div>Calle 24-D Bis No. 73-C - 48, Tels: (601) 548 1317 - (601) 295 0229 WAPP: 322 423 04 02 Bogotá,
                    D.C. - Colombia</div>
                <div class="footer-contact">comunicaciones@fondep.com.co • www.fondep.com.co</div>
            </div>

            <br>

            <!-- Información Personal Section -->
            <table class="main-table">
                <tr>
                    <td colspan="4" class="header">INFORMACIÓN PERSONAL - DEUDOR SOLIDARIO 2</td>
                </tr>
                <tr>
                    <td width="33%">PRIMER APELLIDO<br><input type="text"
                            value="{{ $tercero['primer_apellido'] }}"></td>
                    <td width="33%">SEGUNDO APELLIDO<br><input type="text"
                            value="{{ $tercero['segundo_apellido'] }}"></td>
                    <td colspan="2">NOMBRES<br><input type="text" value="{{ $tercero['nombres'] }}"></td>
                </tr>
                <tr>
                    <td colspan="2">EMPRESA<br><input type="text" value="{{ $asociado['empresa'] }}"></td>
                    <td>CARGO<br><input type="text"></td>
                    <td>ANTIGÜEDAD<br><input type="text"></td>
                </tr>
                <tr>
                    <td>
                        FECHA DE NACIMIENTO<br>
                        <div style="display: flex; gap: 5px;">
                            <input type="number" placeholder="DIA" value="{{ $fechaNacimiento->format('d') }}"
                                style="width: 30px;">
                            <input type="number" placeholder="MES" value="{{ $fechaNacimiento->format('m') }}"
                                style="width: 30px;">
                            <input type="number" placeholder="AÑO" value="{{ $fechaNacimiento->format('Y') }}"
                                style="width: 50px;">
                        </div>
                    </td>
                    <td>CÉDULA<br><input type="text" value="{{ $tercero['tercero_id'] }}"></td>
                    <td>EXPEDIDA EN<br><input type="text"></td>
                    <td>
                        EMPLEADO ACTIVO <input type="checkbox" {{ $asociado['habil'] ? 'SI' : 'NO' }}>
                        PENSIONADO <input type="checkbox">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">DIRECCIÓN RESIDENCIA<br><input type="text"
                            value="{{ $tercero['direccion'] }}"></td>
                    <td>TELÉFONO<br><input type="text" value="{{ $tercero['telefono'] }}"></td>
                    <td>CELULAR<br><input type="text" value="{{ $tercero['celular'] }}"></td>
                </tr>
                <tr>
                    <td>CIUDAD<br><input type="text"></td>
                    <td>DEPARTAMENTO<br><input type="text"></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="2">DIRECCIÓN LABORAL<br><input type="text"
                            value="{{ $asociado['direccion_empresa'] }}"></td>
                    <td>TELÉFONO<br><input type="text" value="{{ $asociado['telefono_empresa'] }}"></td>
                    <td></td>
                </tr>
                <tr>
                    <td>CIUDAD<br><input type="text"></td>
                    <td>DEPARTAMENTO<br><input type="text"></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="3">CORREO ELECTRÓNICO (E-MAIL)<br><input type="email"
                            value="{{ $tercero['email'] }}"></td>
                    <td>
                        ENVÍO CORRESPONDENCIA<br>
                        OFICINA <input type="checkbox">
                        RESIDENCIA <input type="checkbox">
                    </td>
                </tr>
            </table>

            <br><br>


            <!-- Información Financiera Section -->
            <table class="main-table">
                <tr>
                    <td colspan="4" class="header">INFORMACIÓN FINANCIERA - DEUDOR SOLIDARIO 2</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; font-weight: bold;">INGRESOS MENSUALES</td>
                    <td colspan="2" style="text-align: center; font-weight: bold;">EGRESOS MENSUALES</td>
                </tr>
                <tr>
                    <td>SALARIO y/o PENSIÓN</td>
                    <td>$ <input type="number"></td>
                    <td>ARRIENDO/CUOTA VIVIENDA</td>
                    <td>$ <input type="number"></td>
                </tr>
                <tr>
                    <td>OTROS INGRESOS*</td>
                    <td>$ <input type="number"></td>
                    <td>GASTOS PERSONALES/FAMILIARES</td>
                    <td>$ <input type="number"></td>
                </tr>
                <tr>
                    <td>TOTAL INGRESOS</td>
                    <td>$ <input type="number"></td>
                    <td>TOTAL EGRESOS</td>
                    <td>$ <input type="number"></td>
                </tr>
                <tr>
                    <td colspan="4" class="description">* DESCRIPCIÓN OTROS INGRESOS</td>
                </tr>
                <tr>
                    <td colspan="2" class="header-gray">ACTIVOS</td>
                    <td colspan="2" class="header-gray">PASIVOS</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table width="100%" style="padding: 5px;">
                            <tr>
                                <td colspan="3">FINCA RAÍZ</td>
                            </tr>
                            <tr>
                                <td>TIPO <input type="text"></td>
                                <td>CIUDAD <input type="text"></td>
                                <td>VALOR COMERCIAL $ <input type="number"></td>
                            </tr>
                            <tr>
                                <td colspan="3">VEHÍCULO</td>
                            </tr>
                            <tr>
                                <td>MARCA <input type="text"></td>
                                <td>MODELO <input type="text"></td>
                                <td>PLACA <input type="text"></td>
                            </tr>
                            <tr>
                                <td colspan="3">VALOR COMERCIAL $ <input type="number"></td>
                            </tr>
                            <tr>
                                <td colspan="2">OTROS ACTIVOS</td>
                                <td>$ <input type="number"></td>
                            </tr>
                            <tr>
                                <td colspan="2">VALOR TOTAL ACTIVOS</td>
                                <td>$ <input type="number"></td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="2">
                        <table width="100%" style="padding: 5px;">
                            <tr>
                                <td>HIPOTECA</td>
                                <td colspan="2">$ <input type="number"></td>
                            </tr>
                            <tr>
                                <td>GASTOS FINANCIEROS (Créditos, T.C., etc.)</td>
                                <td colspan="2">$ <input type="number"></td>
                            </tr>
                            <tr>
                                <td>GASTOS DE SOSTENIMIENTO</td>
                                <td colspan="2">$ <input type="number"></td>
                            </tr>
                            <tr>
                                <td>OTROS PASIVOS</td>
                                <td colspan="2">$ <input type="number"></td>
                            </tr>
                            <tr>
                                <td>VALOR TOTAL PASIVOS</td>
                                <td colspan="2">$ <input type="number"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>


            <br><br><br><br><br>
            <br><br><br><br><br>
            <br><br><br><br><br>
            <br><br><br><br><br>
            <br><br><br><br><br>
            <br><br><br><br><br>

            <div class="footer">
                <div class="footer-title">
                    FONDO NACIONAL DE EMPLEADOS, TRABAJADORES Y PENSIONADOS DEL SECTOR POSTAL<br>
                    DE LAS COMUNICACIONES, ENTIDADES AFINES Y COMPLEMENTARIAS - FONDEP
                </div>
                <div>Calle 24-D Bis No. 73-C - 48, Tels: (601) 548 1317 - (601) 295 0229 WAPP: 322 423 04 02 Bogotá,
                    D.C. - Colombia</div>
                <div class="footer-contact">comunicaciones@fondep.com.co • www.fondep.com.co</div>
            </div>


            <br><br><br><br><br>
            <br><br><br><br><br>
            <br><br><br><br><br>

            <!-- Firmas Section -->
            <table class="main-table">
                <tr>
                    <td colspan="3" style="text-align: center; font-weight: bold; border-bottom: 1px dashed #000;">
                        <div style="display: flex; justify-content: space-between; padding: 10px;">
                            <div>Firma, C.C. y huella del solicitante</div>
                            <div>Firma, C.C. y huella deudor solidario 1</div>
                            <div>Firma, C.C. y huella deudor solidario 2</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding: 10px;">
                        <p style="text-align: justify; margin: 0;">
                            El(los) abajo firmante(s), de manera expresa, declara(mos) adeudar solidariamente a FONDEP
                            la suma de dinero solicitada en mutuo con intereses, mediante la presente solicitud, una vez
                            la misma sea aprobada y me(nos) obligo(amos) a pagar en la ciudad de Bogotá, la suma de
                            dinero a que hace referencia esta solicitud, a la tasa de interés que corresponda a la línea
                            de crédito aprobada, en la forma y plazo indicados en este documento. Acepto(amos)
                            expresamente que FONDEP, haga exigible la totalidad de la obligación referida, de
                            presentarse mora en el pago de una o varias de las cuotas establecidas dentro del plazo y en
                            general de configurarse uno cualquiera de los eventos establecidos como extintivos del
                            plazo, tanto en el pagaré como en la carta de instrucciones para llenar el pagaré en blanco
                            que diligencio(amos) para garantizar el pago de la obligación aquí contenida.
                        </p>
                        <p style="text-align: justify; margin: 10px 0;">
                            En caso de mora, autorizo expresa e irrevocablemente a FONDEP, abonar del saldo de mis
                            depósitos de ahorro voluntario, o cualquier suma a mi favor, a las cuotas de crédito
                            solicitado. De igual manera, en caso de que la mora supere los noventa (90) días, autorizo
                            para abonar del saldo de mi ahorro voluntario, el valor de los honorarios y gastos de
                            cobranza que se originen, a FONDEP o a la entidad que ésta contrate para tal fin. Lo
                            anterior sin que requiera previo aviso al deudor.
                        </p>
                        <p style="text-align: justify; margin: 10px 0;">
                            Así mismo, manifiesto(amos) que acepto(amos) el cargo del valor del seguro de vida a
                            deudores y en caso de generarse algún devuelto, me(nos) comprometo(emos) a cancelarlo dentro
                            de los cinco (5) primeros días de cada mes.
                        </p>
                        <p style="text-align: justify; margin: 10px 0;">
                            Declaro(amos) que conozco(cemos) el reglamento de crédito de la entidad y el plan de pagos
                            correspondiente al crédito solicitado.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"
                        style="text-align: center; font-weight: bold; padding: 10px; background-color: #f5f5f5;">
                        AUTORIZACIÓN CONSULTA Y REPORTE A CENTRALES DE RIESGO
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding: 10px;">
                        <p style="text-align: justify; margin: 0;">
                            El(los) abajo firmante(s) autorizo(amos) al Fondo Nacional de Empleados, trabajadores y
                            Pensionados del sector postal de las comunicaciones, entidades afines y complementarias
                            FONDEP o a quien represente sus derechos u ostente en el futuro la calidad de acreedor, a
                            reportar, procesar, solicitar y divulgar a cualquier entidad que maneje o administre bases
                            de datos que contengan el comportamiento crediticio o comercial de personas, toda
                            información.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <div style="display: flex; justify-content: space-between; padding: 10px; text-align: center;">
                            <div style="border-top: 1px dashed #000; padding-top: 5px; width: 30%;">Firma, C.C. y
                                huella del solicitante</div>
                            <div style="border-top: 1px dashed #000; padding-top: 5px; width: 30%;">Firma, C.C. y
                                huella deudor solidario 1</div>
                            <div style="border-top: 1px dashed #000; padding-top: 5px; width: 30%;">Firma, C.C. y
                                huella deudor solidario 2</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding: 10px;">
                        <div style="font-weight: bold; margin-bottom: 10px;">ANEXAR LA SIGUIENTE DOCUMENTACIÓN</div>
                        <ol style="margin: 0; padding-left: 20px;">
                            <li>Tres (3) últimos comprobantes de pago</li>
                            <li>Fotocopia de la cédula ampliada al 150%</li>
                            <li>Documentos que sirvan de soporte para demostrar ingresos adicionales</li>
                            <li>Pagaré en blanco firmado y con impresión de la huella dactilar</li>
                            <li>Carta de instrucciones diligenciada y con impresión de la huella dactilar</li>
                        </ol>
                    </td>
                </tr>
            </table>

            <br><br><br><br><br>

            <!-- Analisis Section -->
            <table class="second-table">
                <tr>
                    <td colspan="4" style="text-align:center;">
                        ANALISIS DE LA SOLICITUD DE CREDITO POR PARTE DE "FONDEP"
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%;">
                        <div style="border: 1px dashed #000; padding: 5px;">
                            Saldo de aportes sociales:
                        </div>
                    </td>
                    <td style="width: 25%;">
                        $ <input type="text" style="border-bottom: 1px solid #000;">
                    </td>
                    <td style="width: 15%;">
                        <div style="border: 1px dashed #000; padding: 5px;">
                            Estado:
                        </div>
                    </td>
                    <td>
                        <input type="text" style="border-bottom: 1px solid #000;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="border: 1px dashed #000; padding: 5px;">
                            Saldo de crédito a largo plazo:
                        </div>
                    </td>
                    <td>
                        $ <input type="text" style="border-bottom: 1px solid #000;">
                    </td>
                    <td>
                        <div style="border: 1px dashed #000; padding: 5px;">
                            Estado:
                        </div>
                    </td>
                    <td>
                        <input type="text" style="border-bottom: 1px solid #000;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="border: 1px dashed #000; padding: 5px;">
                            Saldo de crédito a corto plazo:
                        </div>
                    </td>
                    <td>
                        $ <input type="text" style="border-bottom: 1px solid #000;">
                    </td>
                    <td>
                        <div style="border: 1px dashed #000; padding: 5px;">
                            Estado:
                        </div>
                    </td>
                    <td>
                        <input type="text" style="border-bottom: 1px solid #000;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="border: 1px dashed #000; padding: 5px;">
                            Saldo de crédito de consumo:
                        </div>
                    </td>
                    <td>
                        $ <input type="text" style="border-bottom: 1px solid #000;">
                    </td>
                    <td>
                        <div style="border: 1px dashed #000; padding: 5px;">
                            Estado:
                        </div>
                    </td>
                    <td>
                        <input type="text" style="border-bottom: 1px solid #000;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Estado de cuenta suministrado y verificado por: <input type="text"
                            style="border-bottom: 1px solid #000;">
                    </td>
                    <td colspan="2">
                        Fecha: <input type="text" style="border-bottom: 1px solid #000;">
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Resultado y observaciones a la verificación de la información telefónica realizada en la fecha
                        de:
                        <input type="text" style="width: 100%; border-bottom: 1px solid #000;">
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Verificación efectuada por: <input type="text"
                            style="width: 70%; border-bottom: 1px solid #000;">
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="border-bottom: 1px dashed #000;">
                        RESULTADO FINAL DE LA SOLICITUD DE CREDITO PRESENTADA
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Solicitud de crédito aprobada por:
                        Junta Directiva<input type="text" style="width: 100px; border-bottom: 1px solid #000;">
                        Comité de crédito<input type="text" style="width: 100px; border-bottom: 1px solid #000;">
                        Gerencia<input type="text" style="width: 100px; border-bottom: 1px solid #000;">
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Según consta en el Acta número:<input type="text"
                            style="width: 100px; border-bottom: 1px solid #000;">
                        Fecha:<input type="text" style="width: 150px; border-bottom: 1px solid #000;">
                        Solicitud negada<input type="text" style="width: 100px; border-bottom: 1px solid #000;">
                        Aplazada<input type="text" style="width: 100px; border-bottom: 1px solid #000;">
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Solicitud Aprobada<input type="text" style="width: 50px; border-bottom: 1px solid #000;">
                        Aprobada por valor de:$<input type="text"
                            style="width: 200px; border-bottom: 1px solid #000;">
                        Plazo aprobado:<input type="text" style="width: 100px; border-bottom: 1px solid #000;">
                        Meses
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Valor cuota mensual: $<input type="text"
                            style="width: 200px; border-bottom: 1px solid #000;">
                    </td>
                    <td colspan="2">
                        Fecha de pago de la primera cuota:<input type="text"
                            style="width: 200px; border-bottom: 1px solid #000;">
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Observaciones formuladas por el órgano directivo sobre la aprobación final del crédito
                        solicitado:
                        <textarea style="width: 100%; height: 50px; border: none; border-bottom: 1px solid #000;"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Crédito desembolsado el día:<input type="text"
                            style="width: 200px; border-bottom: 1px solid #000;">
                    </td>
                    <td colspan="2">
                        Número de comprobante de egreso:<input type="text"
                            style="width: 200px; border-bottom: 1px solid #000;">
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        Número de cheque<input type="text" style="width: 150px; border-bottom: 1px solid #000;">
                        Consignado en banco:<input type="text"
                            style="width: 200px; border-bottom: 1px solid #000;">
                        No. de cuenta:<input type="text" style="width: 200px; border-bottom: 1px solid #000;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Aprobado por:<input type="text" style="width: 100%; border-bottom: 1px solid #000;">
                    </td>
                    <td colspan="2">
                        Firma Aprobado<input type="text" style="width: 100%; border-bottom: 1px solid #000;">
                    </td>
                </tr>
            </table>


            <div class="footer">
                <div class="footer-title">
                    FONDO NACIONAL DE EMPLEADOS, TRABAJADORES Y PENSIONADOS DEL SECTOR POSTAL<br>
                    DE LAS COMUNICACIONES, ENTIDADES AFINES Y COMPLEMENTARIAS - FONDEP
                </div>
                <div>Calle 24-D Bis No. 73-C - 48, Tels: (601) 548 1317 - (601) 295 0229 WAPP: 322 423 04 02 Bogotá,
                    D.C. - Colombia</div>
                <div class="footer-contact">comunicaciones@fondep.com.co • www.fondep.com.co</div>
            </div>

        </div>
    </div>
</body>

</html>
