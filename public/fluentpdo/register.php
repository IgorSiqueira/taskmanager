<?php

require_once __DIR__ . '/../../vendor/autoload.php';


date_default_timezone_set('America/Sao_Paulo');


use App\Application\UseCase\User\Exception\UserAlreadyExistsException;
use App\Application\UseCase\User\RegisterUserUseCase;
use App\Infrastructure\Database\FluentPDO\ConnectionProvider as FluentPDOConnectionProvider;
use App\Infrastructure\Database\FluentPDO\Repository\FluentPDOUserRepository;
use App\Infrastructure\Service\BcryptPasswordHasher;

$pageTitle = 'Registrar Novo Usuário';
$activeSystem = 'FluentPDO'; // Para o header.php
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        $errorMessage = 'Todos os campos são obrigatórios.';
    } elseif ($password !== $confirmPassword) {
        $errorMessage = 'As senhas não coincidem.';
    } else {
        try {
            $connectionProvider = new FluentPDOConnectionProvider();
            $fluentPdo = $connectionProvider->getFluentPDO();

            $userRepository = new FluentPDOUserRepository($fluentPdo);
            $passwordHasher = new BcryptPasswordHasher();

            $registerUserUseCase = new RegisterUserUseCase($userRepository, $passwordHasher);
            $newUser = $registerUserUseCase->execute($name, $email, $password);

            $successMessage = 'User ' . htmlspecialchars($newUser->getName()) . ' registered! ID: ' . $newUser->getId();
            $_POST = [];

        } catch (UserAlreadyExistsException $e) {
            $errorMessage = $e->getMessage();
        } catch (InvalidArgumentException $e) {
            $errorMessage = 'Erro de validação: ' . $e->getMessage();
        } catch (Exception $e) {
            error_log('Erro no registro (register.php): ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            $errorMessage = 'Ocorreu um erro inesperado ao tentar registrar. Por favor, tente novamente mais tarde.';
        }
    }
}


include __DIR__ . '/../../templates/header.php';
?>

    <section id="contact" class="contact section">

    <div class="container section-title" data-aos="fade-up">
        <h2>Cadastre-se</h2>
        <p>Crie seu usuário para utilizar esse micro sistema de teste</p>
    </div><div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center"> <div class="col-lg-8"> <form action="register.php" method="POST" class="php-email-form" data-aos="fade-up" id="registrationForm">
                    <div class="row gy-4">

                        <div class="col-md-12"> <label for="name" class="pb-2">Nome</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" class="form-control" required>
                        </div>

                        <div class="col-md-12"> <label for="email" class="pb-2">E-mail</label>
                            <input class="form-control" type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="pb-2">Senha (mínimo 8 caracteres)</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password" class="pb-2">Confirmar Senha</label>
                            <input class="form-control" type="password" id="confirm_password" name="confirm_password" required>
                        </div>

                        <div class="col-md-12 text-center">
                            <div class="loading" style="display: none;">Loading</div>

                            <?php if (!empty($errorMessage)): ?>
                                <div class="error-message-php" style="display:block; background-color: #f8d7da; color: #721c24; padding: 10px; margin-top: 10px; margin-bottom: 10px; border: 1px solid #f5c6cb; border-radius: 4px;">
                                    <?php echo htmlspecialchars($errorMessage); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($successMessage)): ?>
                                <div class="sent-message-php" style="display:block; background-color: #d4edda; color: #155724; padding: 10px; margin-top: 10px; margin-bottom: 10px; border: 1px solid #c3e6cb; border-radius: 4px;">
                                    <?php echo htmlspecialchars($successMessage); ?>
                                </div>
                            <?php endif; ?>

                            <div class="error-message" style="display: none;"></div>
                            <div class="sent-message" style="display: none;">Your message has been sent. Thank you!</div>

                            <button type="submit" class="btn">Criar usuário</button>
                        </div>
                    </div>
                </form>
            </div></div>
    </div>
    </section><?php
include __DIR__ . '/../../templates/footer.php';
?>