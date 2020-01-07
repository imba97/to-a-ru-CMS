<div class="modal fade" id="alertMessage">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                    <h3>提示</h3>
                </div>
                <div class="modal-body">
                    <p>{{ Session::has('alert') ? Session::get('alert') : '遇到了错误' }}</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">关闭</button>
                </div>
        </div>
    </div>
</div>