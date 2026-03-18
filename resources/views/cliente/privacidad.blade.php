<style>
:root {
    --primary: #e48815;
    --gold: #fccc7c;
    --brown: #8c4030;
    --soft-brown: #b28562;
    --light: #c0a799;
    --dark: #5f4636;
}

/* CONTENEDOR */
.legal-wrapper {
    display: flex;
    min-height: 100vh;
    background: #dfb26f;
    font-family: "Manrope", sans-serif;
}

/* IZQUIERDA */
.legal-left {
    flex: 1;
    padding: 60px 80px;
}

/* HEADER */
.legal-header h1 {
    font-family: "Cinzel", serif;
    font-size: 34px;
    color: var(--brown);
}

.legal-header p {
    color: var(--dark);
    margin-bottom: 40px;
}

/* BLOQUES */
.legal-block {
    display: flex;
    gap: 18px;
    margin-bottom: 30px;
    transition: transform .2s ease;
}

.legal-block:hover {
    transform: translateX(5px);
}

/* ICONOS */
.legal-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(145deg, var(--primary), var(--gold));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

/* TITULOS */
.legal-block h2 {
    color: var(--brown);
    margin-bottom: 5px;
}

/* TEXTO */
.legal-block p {
    font-size: 14px;
    color: #333;
    line-height: 1.6;
}

/* DERECHA */
.legal-right {
    width: 35%;
    background: linear-gradient(135deg, var(--soft-brown), var(--brown));
    position: relative;
    overflow: hidden;
}

/* FIGURAS */
.shape {
    position: absolute;
    transform: rotate(45deg);
    opacity: 0.85;
}

.shape-1 {
    width: 200px;
    height: 200px;
    background: var(--gold);
    top: 20%;
    right: -80px;
}

.shape-2 {
    width: 150px;
    height: 150px;
    background: var(--primary);
    bottom: 20%;
    right: -60px;
}

.shape-3 {
    width: 100px;
    height: 100px;
    background: var(--light);
    top: 60%;
    right: 40px;
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .legal-wrapper {
        flex-direction: column;
    }

    .legal-right {
        width: 100%;
        height: 150px;
    }
}
</style>


<div class="legal-wrapper">

    <div class="legal-left">

        <div class="legal-header">
            <h1>Política de Privacidad</h1>
            <p>Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <div class="legal-block">
            <div class="legal-icon">📊</div>
            <div>
                <h2>Información recopilada</h2>
                <p>
                    Recopilamos datos personales como nombre, correo electrónico, teléfono y datos necesarios
                    para la gestión de citas dentro del sistema.
                </p>
            </div>
        </div>

        <div class="legal-block">
            <div class="legal-icon">⚙️</div>
            <div>
                <h2>Uso de la información</h2>
                <p>
                    La información se utiliza exclusivamente para la gestión de citas, comunicación con el cliente
                    y mejora del servicio.
                </p>
            </div>
        </div>

        <div class="legal-block">
            <div class="legal-icon">🔒</div>
            <div>
                <h2>Protección de datos</h2>
                <p>
                    Implementamos medidas de seguridad para proteger la información personal contra accesos no autorizados.
                </p>
            </div>
        </div>

        <div class="legal-block">
            <div class="legal-icon">👤</div>
            <div>
                <h2>Derechos del usuario</h2>
                <p>
                    El usuario puede solicitar la modificación o eliminación de sus datos personales en cualquier momento.
                </p>
            </div>
        </div>

    </div>

    <div class="legal-right">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

</div>