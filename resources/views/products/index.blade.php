<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Product Inventory Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #06070d;
            --bg-secondary: #0d0f1a;
            --bg-card: #111425;
            --bg-card-hover: #161a32;
            --bg-input: #0e1024;
            --accent-1: #6c5ce7;
            --accent-2: #a29bfe;
            --neon-cyan: #00cec9;
            --neon-pink: #fd79a8;
            --neon-gold: #fdcb6e;
            --text-primary: #edf0ff;
            --text-secondary: #6b7194;
            --text-muted: #3d4268;
            --border-color: #1a1e3a;
            --border-glow: rgba(108,92,231,.35);
            --shadow-card: 0 4px 24px rgba(0,0,0,.4), 0 1px 3px rgba(0,0,0,.3);
            --shadow-elevated: 0 8px 40px rgba(0,0,0,.55), 0 2px 6px rgba(0,0,0,.3);
            --radius-lg: 16px;
            --radius-md: 12px;
            --radius-sm: 8px;
        }
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Outfit',sans-serif;background:var(--bg-primary);color:var(--text-primary);min-height:100vh;overflow-x:hidden}

        /* ── Starfield ── */
        .starfield{position:fixed;inset:0;z-index:0;pointer-events:none}
        .starfield .star{position:absolute;border-radius:50%;background:#fff}
        .starfield .star.sm{width:1px;height:1px;opacity:.3;animation:twinkle 4s ease-in-out infinite}
        .starfield .star.md{width:2px;height:2px;opacity:.5;animation:twinkle 3s ease-in-out infinite}
        .starfield .star.lg{width:3px;height:3px;opacity:.7;animation:twinkle 5s ease-in-out infinite;box-shadow:0 0 6px 1px rgba(162,155,254,.4)}
        @keyframes twinkle{0%,100%{opacity:.2}50%{opacity:1}}

        /* Aurora glow */
        .aurora{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden}
        .aurora::before,.aurora::after{content:'';position:absolute;border-radius:50%;filter:blur(120px);opacity:.07}
        .aurora::before{width:600px;height:600px;background:var(--accent-1);top:-15%;right:-10%;animation:auroraDrift 30s ease-in-out infinite}
        .aurora::after{width:500px;height:500px;background:var(--neon-cyan);bottom:-10%;left:-5%;animation:auroraDrift 25s ease-in-out infinite reverse}
        @keyframes auroraDrift{0%,100%{transform:translate(0,0) scale(1)}33%{transform:translate(50px,-40px) scale(1.15)}66%{transform:translate(-40px,30px) scale(.9)}}

        /* ── Layout ── */
        .app-shell{position:relative;z-index:1;max-width:1160px;margin:0 auto;padding:2rem 1.5rem 3rem}

        /* ── Header ── */
        .header{text-align:center;margin-bottom:2.5rem;animation:slideUp .5s ease}
        .header-badge{display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,rgba(108,92,231,.15),rgba(0,206,201,.1));border:1px solid rgba(108,92,231,.2);border-radius:40px;padding:.4rem 1.2rem;font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:1.5px;color:var(--accent-2);margin-bottom:1rem}
        .header-badge i{font-size:.6rem}
        .header h1{font-size:2.6rem;font-weight:900;letter-spacing:-1.5px;margin-bottom:.4rem;color:var(--text-primary)}
        .header h1 span{background:linear-gradient(135deg,var(--accent-2),var(--neon-cyan));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
        .header p{color:var(--text-secondary);font-size:.95rem;font-weight:400}
        @keyframes slideUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:none}}

        /* ── Stat Cards ── */
        .stats{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:2rem}
        .stat{background:var(--bg-card);border:1px solid var(--border-color);border-radius:var(--radius-lg);padding:1.5rem;position:relative;overflow:hidden;transition:all .3s;box-shadow:var(--shadow-card);animation:slideUp .5s ease both}
        .stat:nth-child(1){animation-delay:.08s}.stat:nth-child(2){animation-delay:.16s}.stat:nth-child(3){animation-delay:.24s}
        .stat:hover{transform:translateY(-3px);border-color:rgba(108,92,231,.25);box-shadow:var(--shadow-elevated)}
        .stat-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:.8rem}
        .stat-icon{width:40px;height:40px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:1.1rem}
        .stat:nth-child(1) .stat-icon{background:rgba(108,92,231,.12);color:var(--accent-2)}
        .stat:nth-child(2) .stat-icon{background:rgba(0,206,201,.1);color:var(--neon-cyan)}
        .stat:nth-child(3) .stat-icon{background:rgba(253,203,110,.1);color:var(--neon-gold)}
        .stat-indicator{font-size:.65rem;color:var(--neon-cyan);font-weight:600;display:flex;align-items:center;gap:.2rem}
        .stat-val{font-size:1.9rem;font-weight:800;letter-spacing:-1px;line-height:1}
        .stat-label{font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--text-secondary);margin-top:.4rem}
        .stat::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px}
        .stat:nth-child(1)::after{background:linear-gradient(90deg,var(--accent-1),transparent)}
        .stat:nth-child(2)::after{background:linear-gradient(90deg,var(--neon-cyan),transparent)}
        .stat:nth-child(3)::after{background:linear-gradient(90deg,var(--neon-gold),transparent)}

        /* ── Section Card ── */
        .section{background:var(--bg-card);border:1px solid var(--border-color);border-radius:var(--radius-lg);box-shadow:var(--shadow-card);animation:slideUp .5s ease both;overflow:hidden}
        .section-header{padding:1.5rem 1.8rem;border-bottom:1px solid var(--border-color);display:flex;align-items:center;gap:.7rem}
        .section-header i{color:var(--accent-2);font-size:1.1rem}
        .section-header span{font-size:1rem;font-weight:700}
        .section-body{padding:1.8rem}

        /* ── Form ── */
        .form-card{animation-delay:.2s}
        .form-label{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--text-secondary);margin-bottom:.5rem}
        .form-control{background:var(--bg-input);border:1.5px solid var(--border-color);color:var(--text-primary);border-radius:var(--radius-md);padding:.8rem 1rem;font-size:.9rem;font-family:'Outfit',sans-serif;transition:all .25s}
        .form-control:focus{background:var(--bg-secondary);border-color:var(--accent-1);box-shadow:0 0 0 3px var(--border-glow);color:var(--text-primary)}
        .form-control::placeholder{color:var(--text-muted)}
        .btn-primary-custom{background:linear-gradient(135deg,var(--accent-1),#5a4bd4);color:#fff;border:none;border-radius:var(--radius-md);padding:.8rem 1.8rem;font-weight:700;font-size:.9rem;font-family:'Outfit',sans-serif;transition:all .3s;cursor:pointer;position:relative;overflow:hidden}
        .btn-primary-custom:hover{transform:translateY(-2px);box-shadow:0 6px 28px rgba(108,92,231,.5);color:#fff}
        .btn-primary-custom:active{transform:translateY(0)}
        .btn-primary-custom:disabled{opacity:.35;transform:none!important;box-shadow:none!important;cursor:not-allowed}

        /* ── Table ── */
        .table-card{animation-delay:.3s}
        .table-wrap{overflow-x:auto}
        .table{color:var(--text-primary);margin:0}
        .table thead th{background:var(--bg-secondary);border-bottom:1.5px solid var(--border-color);font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1.3px;color:var(--text-secondary);padding:1rem 1.2rem;white-space:nowrap}
        .table tbody td{padding:.9rem 1.2rem;border-bottom:1px solid rgba(26,30,58,.6);vertical-align:middle;font-size:.88rem;transition:background .2s}
        .table tbody tr{transition:background .2s}
        .table tbody tr:hover td{background:rgba(108,92,231,.04)}
        .product-name{font-weight:600}
        .price-tag{color:var(--accent-2);font-weight:600}
        .total-badge{display:inline-flex;align-items:center;gap:.3rem;background:linear-gradient(135deg,rgba(0,206,201,.12),rgba(0,206,201,.05));border:1px solid rgba(0,206,201,.15);color:var(--neon-cyan);padding:.3rem .8rem;border-radius:20px;font-size:.8rem;font-weight:700}
        .grand-row td{background:rgba(0,206,201,.04)!important;border-top:2px solid rgba(0,206,201,.15)!important;font-weight:700}

        /* Action Buttons */
        .btn-act{border:none;border-radius:var(--radius-sm);padding:.4rem .65rem;font-size:.78rem;cursor:pointer;transition:all .25s;display:inline-flex;align-items:center;gap:.2rem}
        .btn-act.edit{background:rgba(108,92,231,.1);color:var(--accent-2)}
        .btn-act.edit:hover{background:rgba(108,92,231,.2);transform:scale(1.08)}
        .btn-act.del{background:rgba(253,121,168,.08);color:var(--neon-pink)}
        .btn-act.del:hover{background:rgba(253,121,168,.18);transform:scale(1.08)}
        .btn-act.save{background:rgba(0,206,201,.1);color:var(--neon-cyan)}
        .btn-act.save:hover{background:rgba(0,206,201,.2);transform:scale(1.08)}
        .btn-act.cancel{background:rgba(107,113,148,.1);color:var(--text-secondary)}
        .btn-act.cancel:hover{background:rgba(107,113,148,.2)}
        .edit-input{background:var(--bg-input);border:1.5px solid var(--accent-1);color:var(--text-primary);border-radius:var(--radius-sm);padding:.35rem .6rem;font-size:.85rem;width:100%;min-width:80px;font-family:'Outfit',sans-serif}
        .edit-input:focus{outline:none;box-shadow:0 0 0 2px var(--border-glow)}

        /* Empty state */
        .empty{text-align:center;padding:3.5rem 1rem;color:var(--text-secondary)}
        .empty-icon{width:72px;height:72px;border-radius:50%;margin:0 auto 1rem;background:rgba(108,92,231,.08);display:flex;align-items:center;justify-content:center;font-size:1.8rem;color:var(--accent-1);opacity:.5}
        .empty p{font-weight:400;font-size:.9rem}

        /* Toast */
        .toast-box{position:fixed;top:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:.6rem}
        .t-toast{background:var(--bg-card);border:1px solid var(--border-color);border-radius:var(--radius-md);padding:1rem 1.4rem;color:var(--text-primary);box-shadow:var(--shadow-elevated);display:flex;align-items:center;gap:.7rem;font-size:.88rem;font-weight:500;animation:tIn .4s cubic-bezier(.16,1,.3,1);min-width:300px}
        .t-toast.ok{border-left:3px solid var(--neon-cyan)}
        .t-toast.err{border-left:3px solid var(--neon-pink)}
        @keyframes tIn{from{transform:translateX(120%);opacity:0}to{transform:none;opacity:1}}
        @keyframes tOut{from{opacity:1}to{opacity:0;transform:translateX(40px)}}

        /* Responsive */
        @media(max-width:768px){
            .stats{grid-template-columns:1fr}
            .header h1{font-size:1.7rem}
            .section-body{padding:1.2rem}
        }
    </style>
</head>
<body>

<!-- Starfield -->
<div class="starfield" id="starfield"></div>
<!-- Aurora Glow -->
<div class="aurora"></div>

<div class="app-shell">
    <!-- Header -->
    <div class="header">
        <div class="header-badge"><i class="bi bi-circle-fill"></i> Inventory Dashboard</div>
        <h1>Product <span>Inventory</span></h1>
        <p>Track, manage, and analyze your product data in real-time</p>
    </div>

    <!-- Stats -->
    <div class="stats">
        <div class="stat">
            <div class="stat-top">
                <div class="stat-icon"><i class="bi bi-box-seam-fill"></i></div>
                <div class="stat-indicator"><i class="bi bi-circle-fill"></i> LIVE</div>
            </div>
            <div class="stat-val" id="statProducts">0</div>
            <div class="stat-label">Total Products</div>
        </div>
        <div class="stat">
            <div class="stat-top">
                <div class="stat-icon"><i class="bi bi-layers-fill"></i></div>
                <div class="stat-indicator"><i class="bi bi-circle-fill"></i> LIVE</div>
            </div>
            <div class="stat-val" id="statUnits">0</div>
            <div class="stat-label">Total Units</div>
        </div>
        <div class="stat">
            <div class="stat-top">
                <div class="stat-icon"><i class="bi bi-coin"></i></div>
                <div class="stat-indicator"><i class="bi bi-circle-fill"></i> LIVE</div>
            </div>
            <div class="stat-val" id="statValue">$0.00</div>
            <div class="stat-label">Total Value</div>
        </div>
    </div>

    <!-- Form -->
    <div class="section form-card mb-4">
        <div class="section-header"><i class="bi bi-plus-circle-fill"></i><span>Add New Product</span></div>
        <div class="section-body">
            <form id="productForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" placeholder="e.g. Wireless Headphones" required>
                        <div class="invalid-feedback" id="err_product_name"></div>
                    </div>
                    <div class="col-md-3">
                        <label for="quantity_in_stock" class="form-label">Quantity in Stock</label>
                        <input type="number" class="form-control" id="quantity_in_stock" name="quantity_in_stock" placeholder="0" min="0" step="1" required>
                        <div class="invalid-feedback" id="err_quantity_in_stock"></div>
                    </div>
                    <div class="col-md-3">
                        <label for="price_per_item" class="form-label">Price per Item ($)</label>
                        <input type="number" class="form-control" id="price_per_item" name="price_per_item" placeholder="0.00" min="0" step="0.01" required>
                        <div class="invalid-feedback" id="err_price_per_item"></div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn-primary-custom w-100" id="submitBtn">
                            <span id="btnText"><i class="bi bi-plus-lg"></i> Add</span>
                            <span id="btnSpinner" class="d-none"><span class="spinner-border spinner-border-sm" style="width:1rem;height:1rem;border-width:2px"></span></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="section table-card">
        <div class="section-header"><i class="bi bi-table"></i><span>Inventory Records</span></div>
        <div class="table-wrap">
            <table class="table" id="productsTable">
                <thead>
                    <tr>
                        <th>#</th><th>Product Name</th><th>Qty in Stock</th>
                        <th>Price / Item</th><th>Date Submitted</th><th>Total Value</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
                <tfoot id="tableFoot"></tfoot>
            </table>
        </div>
    </div>
</div>

<div class="toast-box" id="toastContainer"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
    // ── Starfield generator ──
    const sf = document.getElementById('starfield');
    const sizes = ['sm','md','lg'];
    for(let i=0;i<90;i++){
        const s = document.createElement('div');
        s.className = 'star ' + sizes[Math.floor(Math.random()*3)];
        s.style.left = Math.random()*100+'%';
        s.style.top = Math.random()*100+'%';
        s.style.animationDelay = (Math.random()*6)+'s';
        s.style.animationDuration = (3+Math.random()*4)+'s';
        sf.appendChild(s);
    }

    // ── App Logic ──
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const form = document.getElementById('productForm');
    const tbody = document.getElementById('tableBody');
    const tfoot = document.getElementById('tableFoot');
    const submitBtn = document.getElementById('submitBtn');

    function showToast(msg, type='ok'){
        const c = document.getElementById('toastContainer');
        const t = document.createElement('div');
        t.className = 't-toast ' + type;
        const icon = type==='ok' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
        const clr = type==='ok' ? 'var(--neon-cyan)' : 'var(--neon-pink)';
        t.innerHTML = '<i class="bi '+icon+'" style="color:'+clr+';font-size:1.1rem"></i><span>'+msg+'</span>';
        c.appendChild(t);
        setTimeout(()=>{ t.style.animation='tOut .3s ease forwards'; setTimeout(()=>t.remove(),300); },3500);
    }

    function ajax(method, url, data=null){
        const opts = {method, headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}};
        if(data){opts.headers['Content-Type']='application/json';opts.body=JSON.stringify(data);}
        return fetch(url,opts).then(r=>r.json().then(d=>({ok:r.ok,data:d})));
    }

    function fmt(n){ return '$'+parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,','); }
    function fmtDate(d){
        const dt=new Date(d.replace(' ','T'));
        return dt.toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})+' '+dt.toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'});
    }
    function esc(s){ const d=document.createElement('div'); d.textContent=s; return d.innerHTML; }

    function updateStats(products, grandTotal){
        document.getElementById('statProducts').textContent = products.length;
        document.getElementById('statUnits').textContent = Math.round(products.reduce((s,p)=>s+parseFloat(p.quantity_in_stock),0)).toLocaleString();
        document.getElementById('statValue').textContent = fmt(grandTotal);
    }

    function loadProducts(){
        ajax('GET','/products').then(({ok,data})=>{
            if(!ok) return;
            const prods = data.products;
            tbody.innerHTML = '';
            tfoot.innerHTML = '';
            if(!prods.length){
                tbody.innerHTML = '<tr><td colspan="7"><div class="empty"><div class="empty-icon"><i class="bi bi-inbox"></i></div><p>No products yet — add your first product above!</p></div></td></tr>';
                updateStats([],0);
                return;
            }
            prods.forEach((p,i)=>{
                const tr = document.createElement('tr');
                tr.dataset.id = p.id;
                tr.innerHTML =
                    '<td style="color:var(--text-secondary)">'+(i+1)+'</td>'+
                    '<td class="cell-name product-name">'+esc(p.product_name)+'</td>'+
                    '<td class="cell-qty">'+p.quantity_in_stock+'</td>'+
                    '<td class="cell-price price-tag">'+fmt(p.price_per_item)+'</td>'+
                    '<td><small style="color:var(--text-secondary)">'+fmtDate(p.datetime_submitted)+'</small></td>'+
                    '<td><span class="total-badge">'+fmt(p.total_value)+'</span></td>'+
                    '<td>'+
                        '<button class="btn-act edit me-1" onclick="startEdit(\''+p.id+'\')"><i class="bi bi-pencil-fill"></i></button>'+
                        '<button class="btn-act del" onclick="deleteProduct(\''+p.id+'\')"><i class="bi bi-trash-fill"></i></button>'+
                    '</td>';
                tbody.appendChild(tr);
            });
            tfoot.innerHTML = '<tr class="grand-row"><td colspan="5" style="text-align:right;padding-right:1rem;font-size:.9rem"><i class="bi bi-calculator me-1"></i>Grand Total</td><td><span class="total-badge">'+fmt(data.grand_total)+'</span></td><td></td></tr>';
            updateStats(prods, data.grand_total);
        });
    }

    form.addEventListener('submit', function(e){
        e.preventDefault();
        document.querySelectorAll('.form-control').forEach(el=>el.classList.remove('is-invalid'));
        const payload = {
            product_name: document.getElementById('product_name').value.trim(),
            quantity_in_stock: document.getElementById('quantity_in_stock').value,
            price_per_item: document.getElementById('price_per_item').value
        };
        document.getElementById('btnText').classList.add('d-none');
        document.getElementById('btnSpinner').classList.remove('d-none');
        submitBtn.disabled = true;
        ajax('POST','/products',payload).then(({ok,data})=>{
            document.getElementById('btnText').classList.remove('d-none');
            document.getElementById('btnSpinner').classList.add('d-none');
            submitBtn.disabled = false;
            if(ok){ showToast(data.message); form.reset(); loadProducts(); }
            else {
                showToast(data.message||'Validation failed.','err');
                if(data.errors) Object.keys(data.errors).forEach(k=>{
                    const el=document.getElementById(k); if(el) el.classList.add('is-invalid');
                    const err=document.getElementById('err_'+k); if(err) err.textContent=data.errors[k][0];
                });
            }
        });
    });

    window.startEdit = function(id){
        const tr = tbody.querySelector('tr[data-id="'+id+'"]');
        if(!tr) return;
        const name=tr.querySelector('.cell-name').textContent, qty=tr.querySelector('.cell-qty').textContent;
        const price=tr.querySelector('.cell-price').textContent.replace(/[$,]/g,'');
        tr.querySelector('.cell-name').innerHTML = '<input class="edit-input" id="edit-name-'+id+'" value="'+esc(name)+'">';
        tr.querySelector('.cell-qty').innerHTML = '<input class="edit-input" type="number" min="0" id="edit-qty-'+id+'" value="'+qty+'">';
        tr.querySelector('.cell-price').innerHTML = '<input class="edit-input" type="number" min="0" step="0.01" id="edit-price-'+id+'" value="'+price+'">';
        tr.querySelector('td:last-child').innerHTML =
            '<button class="btn-act save me-1" onclick="saveEdit(\''+id+'\')"><i class="bi bi-check-lg"></i></button>'+
            '<button class="btn-act cancel" onclick="loadProducts()"><i class="bi bi-x-lg"></i></button>';
    };

    window.saveEdit = function(id){
        const payload = {
            product_name: document.getElementById('edit-name-'+id).value.trim(),
            quantity_in_stock: document.getElementById('edit-qty-'+id).value,
            price_per_item: document.getElementById('edit-price-'+id).value
        };
        ajax('PUT','/products/'+id,payload).then(({ok,data})=>{
            if(ok){ showToast(data.message); loadProducts(); }
            else showToast(data.message||'Update failed.','err');
        });
    };

    window.deleteProduct = function(id){
        if(!confirm('Are you sure you want to delete this product?')) return;
        ajax('DELETE','/products/'+id).then(({ok,data})=>{
            if(ok){ showToast(data.message); loadProducts(); }
            else showToast(data.message||'Delete failed.','err');
        });
    };

    loadProducts();
})();
</script>
</body>
</html>
