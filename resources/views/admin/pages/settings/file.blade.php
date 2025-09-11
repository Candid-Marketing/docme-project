@extends('admin.dashboard')

@section('content')
<div class="main">
    <div class="container-fluid">
        <div class="file-manager-wrapper">
            <div class="file-manager-card">
                <div class="d-flex justify-content-between align-items-center mb-4 file-manager-header">
                    <h1 class="file-manager-h1 mb-0">File Manager</h1>
                    <button class="btn" data-bs-toggle="modal" data-bs-target="#addFolderModal" style="background-color: #3b68b2; color: white;">
                        Customise new folder
                    </button>
                 </div>
                <div class="row justify-content-center">
                    @php
                        $files = [
                            ['route' => 'admin.file.loan', 'img' => 'bank_loan.png', 'modal' => false, 'title' => 'Property Loan'],
                            ['img' => 'house_loan.png', 'modal' => '#modal2', 'title' => 'Coming Soon'],
                            ['img' => 'assets.png', 'modal' => '#modal3', 'title' => 'Coming Soon'],
                            ['img' => 'shares.png', 'modal' => '#modal4', 'title' => 'Coming Soon'],
                            ['img' => 'company.png', 'modal' => '#modal5', 'title' => 'Coming Soon'],
                            ['img' => 'personal_info.png', 'modal' => '#modal6', 'title' => 'Coming Soon'],
                            ['img' => 'car_insurance.png', 'modal' => '#modal7', 'title' => 'Coming Soon'],
                            ['img' => 'file.png', 'modal' => '#modal8', 'title' => 'Coming Soon'],
                            ['img' => 'renew.png', 'modal' => '#modal9', 'title' => 'Coming Soon'],
                        ];
                    @endphp

                    @foreach ($files as $file)
                        <div class="col-md-4 col-sm-6 col-12 mb-4 d-flex justify-content-center">
                            <div class="text-center">
                                @if(isset($file['route']))
                                    <a href="{{ route($file['route']) }}">
                                        <img src="{{ asset('imgs/' . $file['img']) }}" class="img-fluid file-icon">
                                    </a>
                                @else
                                    <img src="{{ asset('imgs/' . $file['img']) }}" class="img-fluid file-icon" data-toggle="modal" data-target="{{ $file['modal'] }}">
                                @endif
                                <h5 class="file-title">{{ $file['title'] }}</h5>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="addFolderModal" tabindex="-1" aria-labelledby="addFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">

      <form action="{{ route('admin.folder.add') }}" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addFolderModalLabel">Add New Folder</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <label for="folder_name">Folder Name</label>
            <input type="text" name="folder_name" class="form-control" required>

            <label class="mt-3">Parent Folder (optional)</label>
            <input type="hidden" name="parent_id" id="selectedParentId">

            <ul class="folder-tree list-unstyled">
              <li>
                <span class="folder-node" data-id="">-- New Folder --</span>
              </li>

              @php
                $renderFolderTree = function($folder) use (&$renderFolderTree) {
                    echo '<li>';
                    echo '<span class="folder-node" data-id="' . $folder->id . '">▸ ' . $folder->folder_name . '</span>';

                    if ($folder->childrenRecursive->count()) {
                        echo '<ul class="ms-3 folder-children d-none">';
                        foreach ($folder->childrenRecursive as $child) {
                            $renderFolderTree($child);
                        }
                        echo '</ul>';
                    }

                    echo '</li>';
                };
              @endphp

              @foreach ($allFolders as $mainFolder)
                {!! $renderFolderTree($mainFolder) !!}
              @endforeach
            </ul>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Create Folder</button>
          </div>
        </div>
      </form>
    </div>
  </div>


  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.folder-node').forEach(function (node) {
        node.addEventListener('click', function (e) {
          e.stopPropagation();

          // Toggle children
          const childrenList = this.nextElementSibling;
          if (childrenList && childrenList.classList.contains('folder-children')) {
            childrenList.classList.toggle('d-none');
            this.classList.toggle('expanded');
          }

          // Clear all selections
          document.querySelectorAll('.folder-node').forEach(el => el.classList.remove('selected'));

          // Mark as selected
          this.classList.add('selected');

          // Set hidden input
          const folderId = this.getAttribute('data-id');
          document.getElementById('selectedParentId').value = folderId;
        });
      });
    });
    </script>



<style>

    @media (min-width: 992px) {
        .modal-fullscreen .modal-content {
            width: 70%;
            border-radius: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            margin-left: 25%;
            margin-top: 5%;
        }

        .modal-fullscreen .modal-body {
            flex-grow: 1;
        }
    }



    .file-manager-wrapper {
        padding: 30px;
    }

    .file-manager-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 12px rgb(9 9 9 / 19%);
        margin: auto;
    }
    .file-manager-h1
    {
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
    }
    .file-manager-header {
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 12px;
        margin-bottom: 25px;
    }

    .file-title {
        margin-top: 12px;
        font-size: 14px;
        font-weight: 600;
    }

    .file-icon {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease-in-out;
    }

    .file-icon:hover {
        transform: scale(1.1);
    }

    @media (max-width: 576px) {
        .main {
                position: static !important;
                width: 100% !important;
                height: auto !important;
                overflow-y: auto !important;
                padding-bottom: 80px; /* so bottom content isn’t cut off */
                margin-top: 45px;
            }

            body, html {
                overflow-x: hidden;
            }


    }

    /* Tree view layout */
.folder-tree {
  border: 1px solid #dee2e6;
  border-radius: 5px;
  padding: 10px 15px;
  max-height: 300px;
  overflow-y: auto;
  background-color: #f9f9f9;
}

.folder-tree li {
  position: relative;
  margin: 5px 0;
  list-style: none;
}

.folder-node {
  cursor: pointer;
  padding: 5px 10px;
  display: flex;
  align-items: center;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.folder-node:hover {
  background-color: #e9ecef;
}

.folder-node::before {
  content: '\1F4C1'; /* folder icon */
  margin-right: 8px;
  font-size: 16px;
}

/* Highlight selected */
.folder-node.selected {
  background-color: #cfe2ff;
  color: #084298;
  font-weight: bold;
}

/* Child container spacing */
.folder-children {
  margin-left: 20px;
  border-left: 2px dashed #dee2e6;
  padding-left: 10px;
}

/* Toggle arrows */
.folder-node::after {
  content: '▸';
  margin-left: auto;
  transition: transform 0.2s;
}

.folder-node.expanded::after {
  transform: rotate(90deg);
}

</style>
@endsection
