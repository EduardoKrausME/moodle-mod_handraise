# mod_handraise - Levantar a mão

Atividade Moodle para organizar pedidos de fala em sala ou em encontros síncronos.

## Fluxo

- o aluno abre a atividade e clica em **Preciso falar**;
- o pedido entra na fila por ordem de chegada;
- o aluno vê sua posição e pode cancelar o pedido;
- o professor vê os nomes em ordem, com horário e tempo de espera;
- ao clicar em **Atendido**, o professor remove aquela pessoa da fila;
- a tela atualiza automaticamente por AJAX, sem recarregar a página.

## Privacidade e segurança

Alunos não recebem os nomes da fila pela API; veem apenas a própria posição e o total de pessoas aguardando. Professores
com a capability `mod/handraise:managequeue` recebem a fila nominal.

As ações usam External Functions do Moodle com validação de contexto e capabilities. A atividade também integra com a
Privacy API, backup e restauração e reset do curso.
