<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Cabeçalho HTML -->
</head>
<body>
    <div class="container">
        <!-- Formulário para editar evento -->
        <h1>Editar Evento</h1>
        <form action="/eventos/{{ $evento->title }}" method="POST">
            @csrf
            @method('PUT')
            <label for="title">Título</label>
            <input type="text" name="title" value="{{ $evento->title }}" required>
            <label for="description">Descrição</label>
            <textarea name="description" required> {{ $evento->description }}</textarea>
            <label for="start">Data de Início</label>
            <input type="date" name="start" value="{{ $evento->start }}" required>
            <label for="end">Data de Encerramento</label>
            <input type="date" name="end" value="{{ $evento->end }}" required>
            <button type="submit">Salvar</button>
        </form>
    </div>
</body>
</html>
