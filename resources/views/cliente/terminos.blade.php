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
.legal-block li {
    font-size: 14px;
    color: #333;
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
            <h1>Términos y condiciones</h1>
            <p>Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <div class="legal-block">
            <div class="legal-icon">📄</div>
            <div>
                <h2>Uso del servicio</h2>
                <ul>
                    <li>El usuario debe proporcionar información verídica.</li>
                    <li>El sistema debe utilizarse únicamente para agendar citas.</li>
                </ul>
            </div>
        </div>

        <div class="legal-block">
            <div class="legal-icon">💳</div>
            <div>
                <h2>Reservaciones y pagos</h2>
                <ul>
                    <li>Se requiere anticipo para confirmar la cita.</li>
                    <li>El comprobante debe ser válido.</li>
                </ul>
            </div>
        </div>

        <div class="legal-block">
            <div class="legal-icon">⚠️</div>
            <div>
                <h2>Cancelaciones</h2>
                <ul>
                    <li>Las cancelaciones tardías pueden perder el anticipo.</li>
                </ul>
            </div>
        </div>

        <div class="legal-block">
            <div class="legal-icon">🛡️</div>
            <div>
                <h2>Responsabilidad</h2>
                <ul>
                    <li>No nos hacemos responsables por datos incorrectos.</li>
                </ul>
            </div>
        </div>

    </div>

    <div class="legal-right">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

</div>