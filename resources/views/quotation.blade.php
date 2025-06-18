<!DOCTYPE html>
<html>
<head>
    <title>Get Quotation</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Quotation Form</h2>

                    <div id="response" class="alert d-none" role="alert"></div>

                    <form id="quotationForm">
                        <div class="mb-3">
                            <input type="text" name="age" placeholder="Ages (comma separated)" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <select name="currency_id" class="form-select" required>
                                <option value="">Select Currency</option>
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Get Quotation</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    console.log(localStorage.getItem('token'));
    document.getElementById('quotationForm').onsubmit = async function(e) {
        e.preventDefault();

        const form = e.target;
        const token = localStorage.getItem('token');
        const responseBox = document.getElementById('response');

        responseBox.classList.add('d-none');
        responseBox.classList.remove('alert-success', 'alert-danger');
        responseBox.innerText = '';

        if (!token) {
            responseBox.innerText = 'You must log in first.';
            responseBox.classList.add('alert', 'alert-danger', 'mt-3');
            responseBox.classList.remove('d-none');
            return;
        }

        const data = {
            age: form.age.value,
            currency_id: form.currency_id.value,
            start_date: form.start_date.value,
            end_date: form.end_date.value
        };

        try {
            const res = await fetch('/api/quotation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify(data)
            });

            let result;
            try {
                result = await res.clone().json();
            } catch (jsonErr) {
                const text = await res.text();
                console.error('Invalid JSON. Raw response:', text);
                throw new Error('Invalid server response. Possible authentication error.');
            }

            if (!res.ok) {
                let message = result.message || 'An error occurred.';
                if (res.status === 422 && result.errors) {
                    message = Object.values(result.errors).flat().join('\n');
                }
                responseBox.innerText = message;
                responseBox.classList.add('alert', 'alert-danger', 'mt-3');
            } else {
                responseBox.innerText = `Total: ${result.total} ${result.currency_id}`;
                responseBox.classList.add('alert', 'alert-success', 'mt-3');
            }

            responseBox.classList.remove('d-none');

        } catch (err) {
            console.error('Caught fetch error:', err);
            responseBox.innerText = 'Network or unexpected error.';
            responseBox.classList.add('alert', 'alert-danger', 'mt-3');
            responseBox.classList.remove('d-none');
        }
    };
</script>
</body>
</html>
