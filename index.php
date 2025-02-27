<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./css/style.css" />
    <title>SaúdeConectada</title>
  </head>

  <body>
    <header class="header content-max">
      <h1 class="logomarca"><span>Saúde</span>Conectada</h1>
      <nav class="menu">
        <ul class="menu-list">
          <li><a href="#inicio">inicio</a></li>
          <li><a href="#servicos">serviços</a></li>
          <li><a href="#contactos">contactos</a></li>
        </ul>

        <button class="loginclicar">
          <a href="auth/index.php">Login</a>
        </button>
      </nav>
    </header>

    <main id="inicio" class="inicio">
      <div class="inicio-text">
        <h1 class="h1"><span>Saúde Conectada</span></h1>
        <p>
        Manter uma boa saúde envolve hábitos diários que cuidam do corpo e da mente. Uma alimentação equilibrada, rica em nutrientes essenciais como frutas, vegetais e proteínas magras, é fundamental. Complementar isso com atividade física regular ajuda a fortalecer o corpo e melhorar o humor. Além disso, é crucial priorizar o descanso adequado e gerenciar o estresse com técnicas como meditação ou hobbies relaxantes. Consultas médicas regulares e evitar hábitos nocivos, como o tabagismo e o consumo excessivo de álcool, completam a base para uma vida saudável e equilibrada.
        </p>

        <a class="botaoS" href="auth/">conheça mais</a>
      </div>

      <picture>
        <img src="./imgs/a (1).png" alt="" />
      </picture>
    </main>

    <section id="servicos" class="servicos">
      <h1 class="h1"><span>nossos serviços</span></h1>

      <ul class="servicos-list">
        <li class="servico">
          <h2>1</h2>
          <p>
            Lorem ipsum dolor sit, amet consectetur adipisicing elit.
            Reprehenderit ea debitis nemo delectus, dolorum, in aspernatur
            numquam
          </p>
          <span>Lorem ipsum dolor sit</span>
        </li>

        <li class="servico">
          <h2>2</h2>
          <p>
            Lorem ipsum dolor sit, amet consectetur adipisicing elit.
            Reprehenderit ea debitis nemo delectus, dolorum, in aspernatur
            numquam
          </p>
          <span>Lorem ipsum dolor sit</span>
        </li>

        <li class="servico">
          <h2>3</h2>
          <p>
            Lorem ipsum dolor sit, amet consectetur adipisicing elit.
            Reprehenderit ea debitis nemo delectus, dolorum, in aspernatur
            numquam
          </p>
          <span>Lorem ipsum dolor sit</span>
        </li>
      </ul>
    </section>

    <article id="contactos" class="contactos-bg">
      <h1 class="h1"><span>contactos</span></h1>
      <div class="contactos">
        <div class="contacto">
          <ul>
            <li>
              <h2>tell</h2>
              <a href="tel:+244-999999999">999-999-999</a>
              <a href="tel:+244-999999999">999-999-999</a>
            </li>

            <li>
              <h2>Horário De Trabalho</h2>
              <p>08:00 ás 15:00</p>
              <p>segunda á sábado</p>
            </li>

            <li>
              <h2>Endereço De Email</h2>
              <a href="mailto:saude_conectada">saude_conectada@gmail.com</a>
            </li>
          </ul>
        </div>

        <div class="mensagem">
          <input type="text" placeholder="nome" />
          <input type="text" placeholder="E-mail" />
          <textarea name="" id="" placeholder="mensagem"></textarea>
          <button class="botao">enviar</button>
        </div>
      </div>
    </article>

    <footer>
      <div>
        <h1 class="logomarca"><span>Saúde</span>Conectada</h1>
        <br />
        <p>© 2024 Saúde Conectada | todos os direitos reservados!</p>
      </div>
    </footer>
  </body>
</html>
