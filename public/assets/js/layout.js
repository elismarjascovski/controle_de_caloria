function inserirAlimento() {
    var tpAlimento = $("#tp_alimento").val();
    var dsAlimento = $("#ds_alimento").val();
    var quantidade = $("#quantidade").val();
    var nuEnergia = $("#nu_energia").val() != "" ? parseFloat($("#nu_energia").val().toString().replace(',', '.')) : "";
    var nuProteina = $("#nu_proteina").val() != "" ? parseFloat($("#nu_proteina").val().toString().replace(',', '.')) : "";
    var nuCarboidrato = $("#nu_carboidrato").val() != "" ? parseFloat($("#nu_carboidrato").val().toString().replace(',', '.')) : "";
    var nuFibra = $("#nu_fibra").val() != "" ? parseFloat($("#nu_fibra").val().toString().replace(',', '.')) : "";
    var boErro = false;
    var msgErro = [];

    if(tpAlimento == "") {
        msgErro['tp_alimento'] = "Selecione um tipo de alimento.";
        boErro = true;
    }
    if(dsAlimento == "") {
        msgErro['ds_alimento'] = "Campo obrigatório.";
        boErro = true;
    }
    if(quantidade == "") {
        msgErro['quantidade'] = "Campo obrigatório.";
        boErro = true;
    } else if (quantidade <= 0) {
        msgErro['quantidade'] = "Quantidade tem que ser maior do que zero.";
        boErro = true;
    }
    if(nuEnergia == "") {
        msgErro['nu_energia'] = "Campo obrigatório.";
        boErro = true;
    } else if (nuEnergia < 0) {
        msgErro['nu_energia'] = "Calorias não pode ser menor que zero.";
        boErro = true;
    }
    if(nuProteina == "") {
        msgErro['nu_proteina'] = "Campo obrigatório.";
        boErro = true;
    } else if (nuProteina < 0) {
        msgErro['nu_proteina'] = "Proteína não pode ser menor que zero.";
        boErro = true;
    }
    if(nuCarboidrato == "") {
        msgErro['nu_carboidrato'] = "Campo obrigatório.";
        boErro = true;
    } else if (nuCarboidrato < 0) {
        msgErro['nu_carboidrato'] = "Carboidrato não pode ser menor que zero.";
        boErro = true;
    }
    if(nuFibra == "") {
        msgErro['nu_fibra'] = "Campo obrigatório.";
        boErro = true;
    } else if (nuFibra < 0) {
        msgErro['nu_fibra'] = "Fibra não pode ser menor que zero.";
        boErro = true;
    }

    if(boErro) {
        document.querySelectorAll('input').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-control is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-control';
            }
        });
        document.querySelectorAll('select').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-select is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-select';
            }
        });
        return false;
    } else {
        $('#alimentoInserirForm').submit();
    }
}

function excluirDadosAlimento(idAlimento, redirecionar) {
    Swal.fire({
        title: 'Deseja mesmo excluir esse alimento?',
        cancelButtonColor: '#dc3545',
        confirmButtonColor: '#0AF04F',
        cancelButtonText: 'NÂO',
        confirmButtonText: 'SIM',
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            const dados = new FormData();
            dados.append('action', 'excluirAlimento');
            dados.append('id_alimento', idAlimento);
    
            fetch('alimento', { 
            method: 'POST',
            body: dados
            })
            .then(response => response.json()) 
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Sucesso',
                        text: data.message,
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK!',
                    }).then((result) => {
                        window.location.href = redirecionar;
                    });
                } else {
                    Swal.fire({
                        title: 'Erro',
                        text: data.message,
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK!',
                    }).then((result) => {
                        window.location.href = redirecionar;
                    });
                }
            })
            .catch(error => {
                console.error('Erro na requisição AJAX:', error);
            });
        } else if (result.isDenied) {
            window.location.href = redirecionar;
        }
    });
}

function verDadosAlimento() {
    var consumo = $("#consumo").val();
    var selectedOption = $("#alimento option:selected");
    var boErro = false;
    var msgErro = [];

    if(consumo == "") {
        msgErro['consumo'] = "Campo obrigatório.";
        boErro = true;
    } else if (consumo <= 0) {
        msgErro['consumo'] = "Consumo tem que ser maior do que zero.";
        boErro = true;
    }
    if(selectedOption.val() == "") {
        msgErro['alimento'] = "Campo obrigatório.";
        boErro = true;
    }

    if(boErro) {
        document.querySelectorAll('input').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-control is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-control';
            }
        });
        document.querySelectorAll('select').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-select is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-select';
            }
        });
        $("#nome").text('-');
        $("#tipo").text('-');
        $("#calorias").text('-');
        $("#proteinas").text('-');
        $("#carboidratos").text('-');
        $("#fibras").text('-');
        return false;
    } else {

        const dados = new FormData();
        dados.append('action', 'pesquisarDadosAlimento');
        dados.append('id_alimento', selectedOption.val());
  
        fetch('alimento', { 
        method: 'POST',
        body: dados
        })
        .then(response => response.json()) 
        .then(data => {
            if (data.status === 'success') {
                var nome = data.dados_alimento.ds_alimento ? data.dados_alimento.ds_alimento.toString() : 'N/A';
                var tipo = data.dados_alimento.tp_alimento ? data.dados_alimento.tp_alimento.toString() : 'N/A';
                var calorias = data.dados_alimento.nu_energia ? (parseFloat(data.dados_alimento.nu_energia.toString().replace(',', '.')) / parseFloat(data.dados_alimento.quantidade)) * consumo : 'N/A';
                var proteinas = data.dados_alimento.nu_proteina ? (parseFloat(data.dados_alimento.nu_proteina.toString().replace(',', '.')) / parseFloat(data.dados_alimento.quantidade)) * consumo : 'N/A';
                var carboidratos = data.dados_alimento.nu_carboidrato ? (parseFloat(data.dados_alimento.nu_carboidrato.toString().replace(',', '.')) / parseFloat(data.dados_alimento.quantidade)) * consumo : 'N/A';
                var fibras = data.dados_alimento.nu_fibra ? (parseFloat(data.dados_alimento.nu_fibra.toString().replace(',', '.')) / parseFloat(data.dados_alimento.quantidade)) * consumo : 'N/A';

                const visModal = new bootstrap.Modal(document.getElementById('modalDadosAlimento'));
                $("#nome_modal").text(nome);
                $("#tipo_modal").text(tipo);
                $("#calorias_modal").text(calorias !== 'N/A' ? (calorias).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 }) : 'N/A');
                $("#proteinas_modal").text(proteinas !== 'N/A' ? (proteinas).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 }) : 'N/A');
                $("#carboidratos_modal").text(carboidratos !== 'N/A' ? (carboidratos).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 }) : 'N/A');
                $("#fibras_modal").text(fibras !== 'N/A' ? (fibras).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 }) : 'N/A');
                visModal.show();
            } else {
              Swal.fire({
                 title: 'Erro',
                 text: data.message,
                 confirmButtonColor: '#dc3545',
                 confirmButtonText: 'OK!',
               });
            }
        })
        .catch(error => {
            console.error('Erro na requisição AJAX:', error);
        });
    }
}

function logarUsuario() {
    var email = $("#email").val();
    var senha = btoa($("#senha").val());
    var boErro = false;
    var msgErro = [];

    if (!email) {
        msgErro['email'] = "Campo obrigatório.";
        boErro = true;
    }
    if (!senha) {
        msgErro['senha'] = "Campo obrigatório.";
        boErro = true;
    }

    if(boErro) {
        document.querySelectorAll('input').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-control is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-control';
            }
        });
        document.querySelectorAll('select').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-select is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-select';
            }
        });
        return false;
    } else {
        $("#senha").val(senha);
        $('#loginForm').submit();
    }
}

function atualizarDadosMeta() {
    var nuCaloriasDiarias = $("#nu_caloriasdiarias").val();

    var boErro = false;
    var msgErro = [];
    if(!nuCaloriasDiarias) {
        msgErro['nu_caloriasdiarias'] = "Campo obrigatório.";
        boErro = true;
    } else if (nuCaloriasDiarias <= 0) {
        msgErro['nu_caloriasdiarias'] = "Valor tem que ser maior que zero.";
        boErro = true;
    }

    if(boErro) {
        document.querySelectorAll('input').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-control is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-control';
            }
        });
        document.querySelectorAll('select').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-select is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-select';
            }
        });
        return false;
    } else {
        $('#atualizarDadosMetaForm').submit();
    }
}

function atualizarDados() {
    var nome = $("#nome").val();
    var email = $("#email").val();
    var nascimento = $("#nascimento").val();
    var peso = $("#peso").val();
    var altura = $("#altura").val();
    var dsGenero = $("#ds_genero").val();
    var boErro = false;
    var msgErro = [];

    if (!nome) {
        msgErro['nome'] = "Campo obrigatório.";
        boErro = true;
    }
    if (!email) {
        msgErro['email'] = "Campo obrigatório.";
        boErro = true;
    }
    if (!nascimento) {
        msgErro['nascimento'] = "Campo obrigatório.";
        boErro = true;
    }
    if (!peso) {
        msgErro['peso'] = "Campo obrigatório.";
        boErro = true;
    }
    if (!altura) {
        msgErro['altura'] = "Campo obrigatório.";
        boErro = true;
    }
    if (!dsGenero) {
        msgErro['ds_genero'] = "Campo obrigatório.";
        boErro = true;
    }

    if(boErro) {
        document.querySelectorAll('input').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-control is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-control';
            }
        });
        document.querySelectorAll('select').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-select is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-select';
            }
        });
        return false;
    } else {
        $('#atualizarDadosForm').submit();
    }
}

function cadastrarDados() {
    var nome = $("#nome").val();
    var senha = btoa($("#senha").val());
    var nascimento = $("#nascimento").val();
    var email = $("#email").val();
    var boErro = false;
    var msgErro = [];

    if (!nome) {
        msgErro['nome'] = "Campo obrigatório.";
        boErro = true;
    }
    if (!senha) {
        msgErro['senha'] = "Campo obrigatório.";
        boErro = true;
    }   
    if (!nascimento) {
        msgErro['nascimento'] = "Campo obrigatório.";
        boErro = true;
    }    
    if (!email) {
        msgErro['email'] = "Campo obrigatório.";
        boErro = true;
    }

    if(boErro) {
        document.querySelectorAll('input').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-control is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-control';
            }
        });
        document.querySelectorAll('select').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-select is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-select';
            }
        });
        return false;
    } else {
        $("#senha").val(senha);
        $('#cadastrarDadosForm').submit();
    }
}

function inserirRefeicao() {
    const visModal = new bootstrap.Modal(document.getElementById('modalInserirRefeicao'));
    visModal.show();
}

function inserirDadosRefeicao() {
    var dtRefeicao = $("#dt_refeicao").val();
    var consumo = $("#consumo").val();
    var alimento = $("#alimento").val();
    var tpRefeicao = $("#id_refeicao_tipo").val();
    var boErro = false;
    var msgErro = [];

    if(dtRefeicao == "") {
        msgErro['dt_refeicao'] = "Campo obrigatório.";
        boErro = true;
    }
    if(tpRefeicao == "") {
        msgErro['id_refeicao_tipo'] = "Campo obrigatório.";
        boErro = true;
    }
    if(consumo == "") {
        msgErro['consumo'] = "Campo obrigatório.";
        boErro = true;
    } else if (consumo <= 0) {
        msgErro['consumo'] = "Consumo tem que ser maior do que zero.";
        boErro = true;
    }
    if(alimento == "") {
        msgErro['alimento'] = "Campo obrigatório.";
        boErro = true;
    }

    if(boErro) {
        document.querySelectorAll('input').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-control is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-control';
            }
        });
        document.querySelectorAll('select').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-select is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-select';
            }
        });
        return false;
    } else {

        const dados = new FormData();
        dados.append('action', 'inserirRefeicao');
        dados.append('id_alimento', alimento);
        dados.append('consumo', consumo);
        dados.append('dt_refeicao', dtRefeicao);
        dados.append('tp_refeicao', tpRefeicao);
  
        fetch('refeicao', { 
        method: 'POST',
        body: dados
        })
        .then(response => response.json()) 
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    title: 'Sucesso',
                    text: data.message,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK!',
                }).then((result) => {
                    window.location.href = 'refeicao';
                 });
            } else {
                Swal.fire({
                    title: 'Erro',
                    text: data.message,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK!',
                }).then((result) => {
                    window.location.href = 'refeicao';
                });
            }
        })
        .catch(error => {
            console.error('Erro na requisição AJAX:', error);
        });
    }
}

function excluirRefeicao(dtRefeicao, redirecionar) {
    Swal.fire({
        title: 'Deseja mesmo excluir essa refeição?',
        cancelButtonColor: '#dc3545',
        confirmButtonColor: '#0AF04F',
        cancelButtonText: 'NÂO',
        confirmButtonText: 'SIM',
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            const dados = new FormData();
            dados.append('action', 'excluirRefeicaoCompleta');
            dados.append('dt_refeicao', dtRefeicao);
    
            fetch('refeicaohistorico', { 
            method: 'POST',
            body: dados
            })
            .then(response => response.json()) 
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Sucesso',
                        text: data.message,
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK!',
                    }).then((result) => {
                        window.location.href = redirecionar;
                    });
                } else {
                    Swal.fire({
                        title: 'Erro',
                        text: data.message,
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK!',
                    }).then((result) => {
                        window.location.href = redirecionar;
                    });
                }
            })
            .catch(error => {
                console.error('Erro na requisição AJAX:', error);
            });
        } else if (result.isDenied) {
            window.location.href = redirecionar;
        }
    });
}

function excluirDadosRefeicao(idRefeicao, redirecionar) {
    Swal.fire({
        title: 'Deseja mesmo excluir essa refeição?',
        cancelButtonColor: '#dc3545',
        confirmButtonColor: '#0AF04F',
        cancelButtonText: 'NÂO',
        confirmButtonText: 'SIM',
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            const dados = new FormData();
            dados.append('action', 'excluirRefeicao');
            dados.append('id_refeicao', idRefeicao);
    
            fetch('refeicao', { 
            method: 'POST',
            body: dados
            })
            .then(response => response.json()) 
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Sucesso',
                        text: data.message,
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK!',
                    }).then((result) => {
                        window.location.href = redirecionar;
                    });
                } else {
                    Swal.fire({
                        title: 'Erro',
                        text: data.message,
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK!',
                    }).then((result) => {
                        window.location.href = redirecionar;
                    });
                }
            })
            .catch(error => {
                console.error('Erro na requisição AJAX:', error);
            });
        } else if (result.isDenied) {
            window.location.href = redirecionar;
        }
    });
}

function inserirTipoRefeicao() {
    const visModal = new bootstrap.Modal(document.getElementById('modalInserirTipoRefeicao'));
    visModal.show();
}

function inserirDadosTipoRefeicao() {
    var tpRefeicao = $("#tp_refeicao").val();
    var cor = $("#cor").val();
    var boErro = false;
    var msgErro = [];

    if(tpRefeicao == "") {
        msgErro['tp_refeicao'] = "Campo obrigatório.";
        boErro = true;
    }
    if(cor == "") {
        msgErro['cor'] = "Campo obrigatório.";
        boErro = true;
    }

    if(boErro) {
        document.querySelectorAll('input').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-control is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-control';
            }
        });
        document.querySelectorAll('select').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-select is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-select';
            }
        });
        return false;
    } else {

        const dados = new FormData();
        dados.append('action', 'inserirTipoRefeicao');
        dados.append('tp_refeicao', tpRefeicao);
        dados.append('cor', cor);
  
        fetch('refeicaotipo', { 
        method: 'POST',
        body: dados
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    title: 'Sucesso',
                    text: data.message,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK!',
                }).then((result) => {
                    window.location.href = 'refeicaotipo';
                 });
            } else {
                Swal.fire({
                    title: 'Erro',
                    text: data.message,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK!',
                }).then((result) => {
                    window.location.href = 'refeicaotipo';
                });
            }
        })
        .catch(error => {
            console.error('Erro na requisição AJAX:', error);
        });
    }
}

function alterarTipoRefeicao(idRefeicaoTipo) {
    const dados = new FormData();
    dados.append('action', 'carregarDadosTipoRefeicao');
    dados.append('id_refeicaotipo', idRefeicaoTipo);

    fetch('refeicaotipo', { 
    method: 'POST',
    body: dados
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const visModal = new bootstrap.Modal(document.getElementById('modalAlterarTipoRefeicao'));
            console.log(data.dados);
            $("#id_refeicaotipo").val(data.dados.id);
            $("#tp_refeicao_alterar").val(data.dados.tp_refeicao);
            $("#cor_alterar").val(data.dados.cor);
            visModal.show();
        } else {
            Swal.fire({
                title: 'Erro',
                text: data.message,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK!',
            }).then((result) => {
                window.location.href = 'refeicaotipo';
            });
        }
    })
    .catch(error => {
        console.error('Erro na requisição AJAX:', error);
    });


    const visModal = new bootstrap.Modal(document.getElementById('modalAlterarTipoRefeicao'));
    visModal.show();
}

function alterarDadosTipoRefeicao() {
    var tpRefeicao = $("#tp_refeicao_alterar").val();
    var cor = $("#cor_alterar").val();
    var boErro = false;
    var msgErro = [];

    if(tpRefeicao == "") {
        msgErro['tp_refeicao_alterar'] = "Campo obrigatório.";
        boErro = true;
    }
    if(cor == "") {
        msgErro['cor_alterar'] = "Campo obrigatório.";
        boErro = true;
    }

    if(boErro) {
        document.querySelectorAll('input').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-control is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-control';
            }
        });
        document.querySelectorAll('select').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-select is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-select';
            }
        });
        return false;
    } else {

        const dados = new FormData();
        dados.append('action', 'alterarTipoRefeicao');
        dados.append('id_refeicaotipo', $("#id_refeicaotipo").val());
        dados.append('tp_refeicao', tpRefeicao);
        dados.append('cor', cor);
  
        fetch('refeicaotipo', { 
        method: 'POST',
        body: dados
        })
        .then(response => response.json()) 
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    title: 'Sucesso',
                    text: data.message,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK!',
                }).then((result) => {
                    window.location.href = 'refeicaotipo';
                 });
            } else {
                Swal.fire({
                    title: 'Erro',
                    text: data.message,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK!',
                }).then((result) => {
                    window.location.href = 'refeicaotipo';
                });
            }
        })
        .catch(error => {
            console.error('Erro na requisição AJAX:', error);
        });
    }
}

function excluirTipoRefeicao(idRefeicaoTipo, dsRefeicao) {
    Swal.fire({
        title: 'Deseja mesmo excluir o tipo refeição: ' + dsRefeicao + '?',
        cancelButtonColor: '#dc3545',
        confirmButtonColor: '#0AF04F',
        cancelButtonText: 'NÂO',
        confirmButtonText: 'SIM',
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            const dados = new FormData();
            dados.append('action', 'excluirDadosRefeicao');
            dados.append('id_refeicaotipo', idRefeicaoTipo);
        
            fetch('refeicaotipo', { 
            method: 'POST',
            body: dados
            })
            .then(response => response.json()) 
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Sucesso',
                        text: data.message,
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK!',
                    }).then((result) => {
                        window.location.href = 'refeicaotipo';
                    });
                } else {
                    Swal.fire({
                        title: 'Erro',
                        text: data.message,
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK!',
                    }).then((result) => {
                        window.location.href = 'refeicaotipo';
                    });
                }
            })
            .catch(error => {
                console.error('Erro na requisição AJAX:', error);
            });
        } else if (result.isDenied) {
            window.location.href = 'refeicaotipo';
        }
    });
}

function alterarDadosRefeicao(idRefeicao, redirecionar) {
    const dados = new FormData();
    dados.append('action', 'carregarDadosRefeicao');
    dados.append('id_refeicao', idRefeicao);

    fetch('refeicaotipo', { 
    method: 'POST',
    body: dados
    })
    .then(response => response.json()) 
    .then(data => {
        if (data.status === 'success') {
            const visModal = new bootstrap.Modal(document.getElementById('modalAlterarRefeicao'));
            console.log(data.dados);
            $("#id_refeicao_alterar").val(data.dados.id);
            $("#id_refeicao_tipo_alterar").val(data.dados.id_refeicao_tipo);
            $("#consumo_alterar").val(data.dados.nu_consumo);
            $("#alimento_alterar").val(data.dados.id_alimento);
            $("#dt_refeicao_alterar").val(data.dados.dt_refeicao);
            visModal.show();
        } else {
            Swal.fire({
                title: 'Erro',
                icon: 'error',
                text: data.message,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK!',
            }).then((result) => {
                window.location.href = redirecionar;
            });
        }
    })
    .catch(error => {
        console.error('Erro na requisição AJAX:', error);
    });
}

function alterarRefeicao(redirecionar) {
    var dtRefeicao = $("#dt_refeicao_alterar").val();
    var consumo = $("#consumo_alterar").val();
    var alimento = $("#alimento_alterar").val();
    var tpRefeicao = $("#id_refeicao_tipo_alterar").val();
    var boErro = false;
    var msgErro = [];

    if(dtRefeicao == "") {
        msgErro['dt_refeicao_alterar'] = "Campo obrigatório.";
        boErro = true;
    }
    if(tpRefeicao == "") {
        msgErro['id_refeicao_tipo_alterar'] = "Campo obrigatório.";
        boErro = true;
    }
    if(consumo == "") {
        msgErro['consumo_alterar'] = "Campo obrigatório.";
        boErro = true;
    } else if (consumo <= 0) {
        msgErro['consumo_alterar'] = "Consumo tem que ser maior do que zero.";
        boErro = true;
    }
    if(alimento == "") {
        msgErro['alimento_alterar'] = "Campo obrigatório.";
        boErro = true;
    }

    if(boErro) {
        document.querySelectorAll('input').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-control is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-control';
            }
        });
        document.querySelectorAll('select').forEach(element => {
            if (msgErro[(element['id'])] != undefined) {
            document.querySelector('#'+element['id']).className = 'form-select is-invalid';
            $('#'+element['id']+'FeedbackDescricao').html(msgErro[element['id']]);
            } else {
            document.querySelector('#'+element['id']).className = 'form-select';
            }
        });
        return false;
    } else {

        const dados = new FormData();
        dados.append('action', 'alterarRefeicao');
        dados.append('id_refeicao', $("#id_refeicao_alterar").val());
        dados.append('id_alimento', alimento);
        dados.append('consumo', consumo);
        dados.append('dt_refeicao', dtRefeicao);
        dados.append('tp_refeicao', tpRefeicao);
  
        fetch('refeicao', { 
        method: 'POST',
        body: dados
        })
        .then(response => response.json()) 
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    title: 'Sucesso',
                    icon: 'success',
                    text: data.message,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK!',
                }).then((result) => {
                    window.location.href = redirecionar;
                 });
            } else {
                Swal.fire({
                    title: 'Erro',
                    icon: 'error',
                    text: data.message,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK!',
                }).then((result) => {
                    window.location.href = redirecionar;
                });
            }
        })
        .catch(error => {
            console.error('Erro na requisição AJAX:', error);
        });
    }
}