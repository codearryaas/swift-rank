import { useState, useEffect } from '@wordpress/element';
import {
	TabPanel,
	Card,
	CardBody,
	Notice
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import SchemaTab from './components/SchemaTab';
import ConditionsTab from './components/ConditionsTab';

const SchemaMetabox = () => {
	const { schemaData, postId, nonce } = window.swiftRankMetabox || {};

	const [schemaType, setSchemaType] = useState(schemaData?.schemaType || '');
	const [schemaFields, setSchemaFields] = useState(schemaData?.fields || {});
	const [includeConditions, setIncludeConditions] = useState(
		schemaData?.includeConditions || {
			logic: 'or',
			groups: [
				{
					logic: 'and',
					rules: []
				}
			]
		}
	);

	// Save data to hidden input for form submission
	useEffect(() => {
		const data = {
			schemaType,
			fields: schemaFields,
			includeConditions
		};

		// Create or update hidden input
		let input = document.querySelector('input[name="_schema_template_data"]');
		if (!input) {
			input = document.createElement('input');
			input.type = 'hidden';
			input.name = '_schema_template_data';
			document.getElementById('post').appendChild(input);
		}
		input.value = JSON.stringify(data);

		// Add nonce if not exists
		let nonceInput = document.querySelector('input[name="swift_rank_metabox_nonce"]');
		if (!nonceInput) {
			nonceInput = document.createElement('input');
			nonceInput.type = 'hidden';
			nonceInput.name = 'swift_rank_metabox_nonce';
			nonceInput.value = nonce;
			document.getElementById('post').appendChild(nonceInput);
		}
	}, [schemaType, schemaFields, includeConditions, nonce]);

	const tabs = [
		{
			name: 'schema',
			title: __('Schema', 'swift-rank'),
			className: 'schema-tab',
		},
		{
			name: 'conditions',
			title: __('Conditions', 'swift-rank'),
			className: 'conditions-tab',
		},
	];

	return (
		<div className="schema-template-metabox-wrapper">
			<Card>
				<CardBody style={{ padding: 0 }}>
					<TabPanel
						className="schema-template-tabs"
						activeClass="is-active"
						tabs={tabs}
					>
						{(tab) => {
							if (tab.name === 'schema') {
								return (
									<SchemaTab
										schemaType={schemaType}
										schemaFields={schemaFields}
										onSchemaTypeChange={setSchemaType}
										onSchemaFieldsChange={setSchemaFields}
										onConditionsChange={setIncludeConditions}
									/>
								);
							}

							if (tab.name === 'conditions') {
								return (
									<ConditionsTab
										includeConditions={includeConditions}
										onIncludeChange={setIncludeConditions}
									/>
								);
							}

							return null;
						}}
					</TabPanel>
				</CardBody>
			</Card>
		</div>
	);
};

export default SchemaMetabox;
