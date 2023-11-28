<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Cabeçalho HTML -->
</head>
<body>
    <div class="container">
        <!-- Formulário para criar evento -->
        <h1>Adicionar Evento</h1>
        <form action="/criar-evento" method="POST">
            @csrf
            <label for="title">Título</label>
            <input type="text" name="title" required>
            <label for="description">Descrição</label>
            <textarea name="description" required></textarea>
            <label for="start">Data de Início</label>
            <input type="date" name="start" required>
            <label for="end">Data de Encerramento</label>
            <input type="date" name="end" required>
            <button type="submit">Adicionar</button>
        </form>
    </div>
</body>
</html>
