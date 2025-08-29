function inserimentoForm() {
    return {
        nome: '',
        messaggio: '',
        async invia() {
            const response = await fetch('inserisci.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nome: this.nome })
            });

            const data = await response.json();
            this.messaggio = data.message;

            if (data.success) {
            this.nome = ''; // reset del campo input
            }
        }
    }
}
