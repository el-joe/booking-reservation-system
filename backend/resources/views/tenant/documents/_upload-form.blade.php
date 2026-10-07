<form action="{{ route('tenant.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <input type="hidden" name="documentable_type" value="{{ $documentableType ?? '' }}">
    <input type="hidden" name="documentable_id" value="{{ $documentableId ?? '' }}">

    <div>
        <label for="doc_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            File
        </label>
        <input
            type="file"
            id="doc_file"
            name="file"
            required
            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
        >
    </div>

    <div>
        <label for="doc_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Document Name
        </label>
        <input
            type="text"
            id="doc_name"
            name="name"
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
    </div>

    <div>
        <label for="doc_category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Category
        </label>
        <select
            id="doc_category"
            name="category"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
            <option value="">— Select Category —</option>
            <option value="contract">Contract</option>
            <option value="invoice">Invoice</option>
            <option value="identification">Identification</option>
            <option value="certification">Certification</option>
            <option value="insurance">Insurance</option>
            <option value="other">Other</option>
        </select>
    </div>

    <div>
        <label for="doc_expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Expiry Date
        </label>
        <input
            type="date"
            id="doc_expires_at"
            name="expires_at"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
    </div>

    <div>
        <button
            type="submit"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
            Upload Document
        </button>
    </div>
</form>
