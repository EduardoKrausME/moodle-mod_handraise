# mod_handraise - Levantar a mão

Atividade Moodle para organizar pedidos de fala em sala ou em encontros síncronos.

## Fluxo

- O aluno abre a atividade e clica em **Preciso falar**.
- O pedido entra na fila por ordem de chegada.
- O aluno vê sua posição e pode cancelar o pedido.
- O professor vê os nomes em ordem, com horário e tempo de espera.
- Ao clicar em **Atendido**, o professor remove aquela pessoa da fila.
- A tela atualiza automaticamente por AJAX, sem recarregar a página.

## Privacidade e segurança

- Alunos não recebem os nomes da fila pela API; veem apenas a própria posição e o total aguardando.
- Professores com `mod/handraise:managequeue` recebem a fila nominal.
- As ações usam External Functions do Moodle com validação de contexto e capabilities.
- O plugin implementa Privacy API, backup/restore e reset do curso.

## Compatibilidade

- Moodle 4.5 ou superior.
- PHP compatível com a versão do Moodle instalada.

## Instalação

Copie a pasta `handraise` para `mod/handraise` e conclua a atualização pelo administrador do Moodle.
