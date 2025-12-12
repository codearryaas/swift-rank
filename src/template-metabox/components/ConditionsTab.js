import { useState, useEffect } from '@wordpress/element';
import {
	Button,
	SelectControl,
	FormTokenField,
	Card,
	CardBody,
	Notice
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import Icon from '../../components/Icon';
import ProNotice from '../../components/ProNotice';

const ConditionsTab = ({
	includeConditions,
	onIncludeChange
}) => {
	const [postTypes, setPostTypes] = useState([]);
	const [posts, setPosts] = useState([]);
	const [loading, setLoading] = useState(false);

	// Check if Pro is activated
	const isProActivated = window.swiftRankConfig?.isProActivated || false;

	// Initialize conditions with default structure
	const initializeConditions = (conditions) => {
		if (!conditions || !conditions.groups) {
			return {
				logic: 'or',
				groups: [
					{
						logic: 'and',
						rules: []
					}
				]
			};
		}
		return conditions;
	};

	const [conditionsData, setConditionsData] = useState(initializeConditions(includeConditions));

	useEffect(() => {
		// Fetch public post types
		apiFetch({ path: '/wp/v2/types?context=edit' })
			.then((types) => {
				const publicTypes = Object.entries(types)
					.filter(([key, type]) => type.viewable && key !== 'attachment' && key !== 'sr_template')
					.map(([key, type]) => ({
						id: key,
						name: type.name
					}));
				setPostTypes(publicTypes);
			})
			.catch(() => {
				setPostTypes([
					{ id: 'post', name: 'Posts' },
					{ id: 'page', name: 'Pages' }
				]);
			});

		// Fetch post data for already-selected post IDs
		const selectedPostIds = [];
		if (conditionsData && conditionsData.groups) {
			conditionsData.groups.forEach((group) => {
				if (group.rules) {
					group.rules.forEach((rule) => {
						if (rule.conditionType === 'singular' && rule.value && Array.isArray(rule.value)) {
							selectedPostIds.push(...rule.value);
						}
					});
				}
			});
		}

		// Fetch posts if we have selected IDs
		if (selectedPostIds.length > 0) {
			const uniqueIds = [...new Set(selectedPostIds)];
			apiFetch({
				path: `/wp/v2/search?include=${uniqueIds.join(',')}&per_page=100&type=post`
			})
				.then((results) => {
					const foundPosts = results.map((result) => ({
						id: result.id,
						title: result.title
					}));
					setPosts(foundPosts);
				})
				.catch(() => {
					// Silently fail if we can't fetch posts
				});
		}
	}, []);

	const searchPosts = (search) => {
		if (search.length < 2) {
			return Promise.resolve([]);
		}

		setLoading(true);
		return apiFetch({
			path: `/wp/v2/search?search=${encodeURIComponent(search)}&per_page=20&type=post`
		})
			.then((results) => {
				const suggestions = results.map((result) => ({
					id: result.id,
					title: result.title
				}));
				setPosts(prev => {
					const combined = [...prev, ...suggestions];
					const unique = combined.filter((item, index, self) =>
						index === self.findIndex((t) => t.id === item.id)
					);
					return unique;
				});
				setLoading(false);
				return suggestions.map(s => s.title);
			})
			.catch(() => {
				setLoading(false);
				return [];
			});
	};

	const addGroup = () => {
		// Check if Pro is required for multiple groups
		if (!isProActivated && conditionsData.groups.length >= 1) {
			// Don't add more groups in free version
			return;
		}

		const newConditions = { ...conditionsData };
		newConditions.groups.push({
			logic: 'and',
			rules: []
		});
		setConditionsData(newConditions);
		onIncludeChange(newConditions);
	};

	const removeGroup = (groupIndex) => {
		const newConditions = { ...conditionsData };
		newConditions.groups.splice(groupIndex, 1);
		setConditionsData(newConditions);
		onIncludeChange(newConditions);
	};

	const updateGroupLogic = (groupIndex, logic) => {
		const newConditions = { ...conditionsData };
		newConditions.groups[groupIndex].logic = logic;
		setConditionsData(newConditions);
		onIncludeChange(newConditions);
	};

	const updateConditionsLogic = (logic) => {
		const newConditions = { ...conditionsData };
		newConditions.logic = logic;
		setConditionsData(newConditions);
		onIncludeChange(newConditions);
	};

	const addRule = (groupIndex) => {
		const newConditions = { ...conditionsData };
		newConditions.groups[groupIndex].rules.push({
			conditionType: 'location',
			operator: 'equal_to',
			value: []
		});
		setConditionsData(newConditions);
		onIncludeChange(newConditions);
	};

	const removeRule = (groupIndex, ruleIndex) => {
		const newConditions = { ...conditionsData };
		newConditions.groups[groupIndex].rules.splice(ruleIndex, 1);
		setConditionsData(newConditions);
		onIncludeChange(newConditions);
	};

	const updateRule = (groupIndex, ruleIndex, field, value) => {
		const newConditions = { ...conditionsData };
		newConditions.groups[groupIndex].rules[ruleIndex][field] = value;

		// Reset value when condition type changes
		if (field === 'conditionType') {
			newConditions.groups[groupIndex].rules[ruleIndex].value = [];
		}

		setConditionsData(newConditions);
		onIncludeChange(newConditions);
	};

	const getConditionTypeOptions = () => {
		const options = [
			{ value: 'location', label: __('Location', 'swift-rank') },
			{ value: 'post_type', label: __('Post Type', 'swift-rank') },
			{ value: 'singular', label: __('Singular Post / Page', 'swift-rank') }
		];

		// Add Pro-only condition types
		if (isProActivated) {
			options.push({ value: 'user_role', label: __('User Role', 'swift-rank') });
		}

		return options;
	};

	const getOperatorOptions = () => [
		{ value: 'equal_to', label: __('Equal to', 'swift-rank') },
		{ value: 'not_equal_to', label: __('Not equal to', 'swift-rank') }
	];

	const getLocationOptions = () => {
		const options = [
			{ value: '', label: __('Select location...', 'swift-rank') },
			{ value: 'whole_site', label: __('Whole Site', 'swift-rank') },
			{ value: 'home_page', label: __('Home Page', 'swift-rank') }
		];

		// Add Pro-only locations
		if (isProActivated) {
			options.push({
				value: 'author_archive',
				label: __('Author Archive', 'swift-rank')
			});
		}

		return options;
	};

	const renderValueField = (groupIndex, ruleIndex, rule) => {
		const { conditionType, value } = rule;

		switch (conditionType) {
			case 'location':
				return (
					<SelectControl
						value={(value && value[0]) || ''}
						options={getLocationOptions()}
						onChange={(val) => updateRule(groupIndex, ruleIndex, 'value', val ? [val] : [])}
					/>
				);

			case 'post_type':
				return (
					<FormTokenField
						label=''
						value={(value || []).map(id => {
							const found = postTypes.find(pt => pt.id === id);
							return found ? found.name : id;
						})}
						suggestions={postTypes.map(pt => pt.name)}
						onChange={(tokens) => {
							const selectedIds = tokens.map(token => {
								const found = postTypes.find(pt => pt.name === token);
								return found ? found.id : null;
							}).filter(Boolean);
							updateRule(groupIndex, ruleIndex, 'value', selectedIds);
						}}
						placeholder={__('Select post types...', 'swift-rank')}
					/>
				);

			case 'singular':
				return (
					<FormTokenField
						label=''
						value={(value || []).map(id => {
							const found = posts.find(p => p.id === id);
							return found ? found.title : `#${id}`;
						})}
						suggestions={posts.map(p => p.title)}
						onChange={(tokens) => {
							const selectedIds = tokens.map(token => {
								const found = posts.find(p => p.title === token);
								return found ? found.id : null;
							}).filter(Boolean);
							updateRule(groupIndex, ruleIndex, 'value', selectedIds);
						}}
						onInputChange={searchPosts}
						placeholder={__('Search posts/pages...', 'swift-rank')}
					/>
				);

			case 'user_role':
				const userRoles = window.swiftRankMetabox?.userRoles || [];
				return (
					<>
						<FormTokenField
							label=""
							value={(value || []).map(roleId => {
								const found = userRoles.find(r => r.id === roleId);
								return found ? found.name : roleId;
							})}
							suggestions={userRoles.map(r => r.name)}
							onChange={(tokens) => {
								const selectedRoles = tokens.map(token => {
									const found = userRoles.find(r => r.name === token);
									return found ? found.id : null;
								}).filter(Boolean);
								updateRule(groupIndex, ruleIndex, 'value', selectedRoles);
							}}
							placeholder={__('Select user roles...', 'swift-rank')}
						/>
						<Notice status="warning" isDismissible={false} style={{ marginTop: '12px' }}>
							<strong>{__('Note:', 'swift-rank')}</strong> {__('User Role condition only works on author archive pages. It checks the role of the author being viewed, not the current visitor. Combine with "Location = Author Archive" for best results.', 'swift-rank')}
						</Notice>
					</>
				);

			default:
				return null;
		}
	};

	return (
		<div className="conditions-tab-content">
			<Card>
				<CardBody>
					<div className="conditions-header">
						<h3>{__('Display Conditions', 'swift-rank')}</h3>
						<p className="description">
							{__('Control where this schema template appears using condition groups and rules.', 'swift-rank')}
						</p>
					</div>

					{isProActivated && (
						<div className="group-logic-section">
							<label className="group-logic-label">{__('Group Logic', 'swift-rank')}</label>
							<div className="group-logic-buttons">
								<button
									type="button"
									className={`logic-button ${conditionsData.logic === 'or' ? 'is-active' : ''}`}
									onClick={() => updateConditionsLogic('or')}
								>
									{__('OR', 'swift-rank')}
								</button>
								<button
									type="button"
									className={`logic-button ${conditionsData.logic === 'and' ? 'is-active' : ''}`}
									onClick={() => updateConditionsLogic('and')}
								>
									{__('AND', 'swift-rank')}
								</button>
							</div>
							<p className="logic-help">
								{conditionsData.logic === 'or'
									? __('Display if ANY group matches', 'swift-rank')
									: __('Display if ALL groups match', 'swift-rank')}
							</p>
						</div>
					)}

					{conditionsData.groups.map((group, groupIndex) => (
						<div key={groupIndex} className="condition-group">
							<div className="group-header">
								<div className="group-title">
									<span className="group-label">
										{__('Group', 'swift-rank')} {String.fromCharCode(65 + groupIndex)}
									</span>
									<div className="rule-logic-buttons">
										<button
											type="button"
											className={`logic-button-small ${group.logic === 'and' ? 'is-active' : ''}`}
											onClick={() => updateGroupLogic(groupIndex, 'and')}
										>
											{__('AND', 'swift-rank')}
										</button>
										<button
											type="button"
											className={`logic-button-small ${group.logic === 'or' ? 'is-active' : ''}`}
											onClick={() => updateGroupLogic(groupIndex, 'or')}
										>
											{__('OR', 'swift-rank')}
										</button>
									</div>
								</div>
								{conditionsData.groups.length > 1 && (
									<Button
										isDestructive
										isSmall
										onClick={() => removeGroup(groupIndex)}
										className="remove-group-btn"
									>
										{__('Remove Group', 'swift-rank')}
									</Button>
								)}
							</div>

							<div className="rules-list">
								{group.rules.length === 0 && (
									<div className="no-rules-message">
										<p>{__('No rules yet. Click "Add Rule" to create one.', 'swift-rank')}</p>
									</div>
								)}

								{group.rules.map((rule, ruleIndex) => (
									<div key={ruleIndex} className="condition-rule">
										<div className="rule-fields">
											<div className="rule-field">
												<label>{__('Condition Type', 'swift-rank')}</label>
												<SelectControl
													value={rule.conditionType}
													options={getConditionTypeOptions()}
													onChange={(value) => updateRule(groupIndex, ruleIndex, 'conditionType', value)}
												/>
											</div>

											{rule.conditionType !== 'whole_site' && (
												<div className="rule-field">
													<label>{__('Operator', 'swift-rank')}</label>
													<SelectControl
														value={rule.operator}
														options={getOperatorOptions()}
														onChange={(value) => updateRule(groupIndex, ruleIndex, 'operator', value)}
													/>
												</div>
											)}

											<div className="rule-field rule-field-value">
												<label>{__('Value', 'swift-rank')}</label>
												{renderValueField(groupIndex, ruleIndex, rule)}
											</div>
										</div>

										<Button
											isDestructive
											isSmall
											onClick={() => removeRule(groupIndex, ruleIndex)}
											className="remove-rule-btn"
											icon={<Icon name="trash-2" size={16} />}
										>
											{__('Remove', 'swift-rank')}
										</Button>
									</div>
								))}

								<Button
									variant="secondary"
									onClick={() => addRule(groupIndex)}
									className="add-rule-btn"
								>
									{__('+ Add Rule', 'swift-rank')}
								</Button>
							</div>

							{groupIndex < conditionsData.groups.length - 1 && isProActivated && (
								<div className="group-divider">
									<span className="divider-logic">{conditionsData.logic.toUpperCase()}</span>
								</div>
							)}
						</div>
					))}

					<div className="add-group-footer">
						{!isProActivated && conditionsData.groups.length >= 1 ? (
							<ProNotice
								message={__('Multiple condition groups require Swift Rank Pro.', 'swift-rank')}
								linkText={__('Upgrade to Pro', 'swift-rank')}
								compact={true}
							/>
						) : (
							<Button
								variant="primary"
								onClick={addGroup}
								className="add-group-btn"
							>
								{__('+ Add Group', 'swift-rank')}
							</Button>
						)}
					</div>
				</CardBody>
			</Card>
		</div>
	);
};

export default ConditionsTab;
